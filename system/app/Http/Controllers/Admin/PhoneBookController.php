<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ContactImport;
use App\Models\Domain;
use App\Models\Group;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Reseller;
use App\Models\UserWallet;
use App\Models\SenderId;
use App\Jobs\ContactUploadProcess;
use function React\Promise\all;

class PhoneBookController extends Controller
{
    public function groups()
    {
        $title = 'Group List';
        if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
            $datas = Group::where('user_id', Auth::user()->id)->where('type', 'Public');
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $datas = Group::query();
            } else {
                $datas = Group::where('reseller_id', Auth::user()->reseller_id)->where('type', 'Public');
            }
        }

        $tableHeaders = [
            "id" => "#",
            "name" => "Group Name",
            'user' => "User",
            'count' => 'Total Number',
            'status' => 'Status',
            'action' => 'Manage'
        ];

        if (empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != config('constants.USER_GROUP')) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 3), ["type" => 'Type', "reseller" => 'Reseller'], array_slice($tableHeaders, 3));
        }

        $ajaxUrl = route('phonebook.group.list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('reseller', function ($row) {
                    return ($row->reseller_id) ? $row->reseller->reseller_name : '-';
                })
                ->addColumn('count', function ($row) {
                    return Contact::where('group_id', $row->id)->count();
                })
                ->addColumn('status', function ($row) {
                    $st = (ucfirst(strtolower($row['status'])) == 'Active') ? 'primary' : 'danger';
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('phonebook.group.view', $row['id']) . '" class="primary mr-1 ajax-form"><i class="fa fa-eye"></i></a>' .
                        '<a href="' . route('phonebook.group.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
//        exit;

        return view('backend.pages.phonebook.group.list', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    public function groupCreate()
    {
        $title = 'Add New Group';
        return view('backend.pages.phonebook.group.add', compact('title'));
    }

    public function groupStore(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', Rule::unique('group')->where(function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })],
        ]);
        if (isset($request->type)) {
            $type = $request->type;
        } else {
            $type = 'Public';
        }
        $data = [
            'name' => $request->name,
            'type' => $type,
            'user_id' => Auth::user()->id,
            'reseller_id' => Auth::user()->reseller_id,
        ];

        Group::create($data);
        Session::flash('message', 'Group Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('phonebook.group.list')]);
    }

    public function groupView(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        if ($group) {
            $title = 'View Group "' . $group->name . '"';
            if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
                $query = Contact::where('user_id', Auth::user()->id)->where('group_id', $id);
            } else {
                if (empty(Auth::user()->reseller_id)) {
                    $query = Contact::where('group_id', $id);
                } else {
                    $query = Contact::where('reseller_id', Auth::user()->reseller_id)->where('group_id', $id);
                }
            }
            $token = $request->common_field;
            if ($token != '') {
                $query = $query->where(function ($query) use ($token) {
                    $query->orWhere('name_en', 'like', "%$token%")
                        ->orWhere('name_bn', 'like', "%$token%")
                        ->orWhere('phone', 'like', "$token%")
                        ->orWhere('email', 'like', "%$token%");
                });
            }

            if ($request->division != '') {
                $query->where('division', $request->division);
            }

            if ($request->district != '') {
                $query->where('district', $request->district);
            }

            if ($request->upazilla != '') {
                $query->where('upazilla', $request->upazilla);
            }

            if ($request->gender != '') {
                $query->where('gender', $request->gender);
            }

            if ($request->profession != '') {
                $query->where('profession', $request->profession);
            }

            if ($request->age != '') {
                $age_range = explode("-", $request->age);
                $min_age = count($age_range) > 1 ? $age_range[0] : $request->age;
                $max_age = count($age_range) > 1 ? $age_range[1] : $request->age;
                $query->whereBetween(DB::raw('timestampdiff(year, dob, curdate())'), [$min_age, $max_age]);
            }

            if ($request->blood_group != '') {
                $query->where('blood_group', $request->blood_group);
            }

            $query->select('*', DB::raw('timestampdiff(year, dob, curdate()) as age'));


            $tableHeaders = ["check" => "", "sl" => "#", "id" => "Hidden", "name_en" => "Name", "phone" => "Phone Number", "email" => "Email",
                "age" => "Age", "gender" => "Gender", 'blood_group' => 'Blood Group', "division" => "Division", "district" => "District", "upazilla" => "Upazilla", "status" => "Status", "action" => "Action"];


            if (empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != config('constants.USER_GROUP')) {
                $tableHeaders = array_merge(array_slice($tableHeaders, 0, 12), ["reseller" => 'Reseller'], array_slice($tableHeaders, 12));
            }

            if ($this->ajaxDatatable()) {
                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('check', function ($row) {
                        return '';
                    })
                    ->addColumn('sl', function ($row) {
                        return $row['id'];
                    })
                    ->addColumn('status', function ($row) {
                        $st = ($row['status'] == 'Active') ? 'primary' : 'danger';
                        $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                        return $status_btn;
                    })
                    ->addColumn('reseller', function ($row) {
                        $reseller = ($row['reseller_id']) ? $row['reseller']->reseller_name : "-";
                        return $reseller;
                    })
                    ->addColumn('group', function ($row) {
                        $group = ($row['group_id']) ? $row['group']->name : "-";
                        return $group;
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="' . route('phonebook.contact.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                        return $btn;
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            }

            $ajaxUrl = route('phonebook.group.view', $id);

            $divisions = allDivision();
            $professions = Contact::whereNotNull('profession')->distinct()->get(['profession']);

            return view('backend.pages.phonebook.group.view', compact('title', 'professions', 'tableHeaders', 'ajaxUrl', 'group', 'divisions'));
        } else {
            Session::flash('message', 'Group Not Found!');
            Session::flash('m-class', 'alert-danger');
            return json_encode(['url' => route('phonebook.group.list')]);
        }
    }

    public function groupEdit($id)
    {
        if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
            $group = Group::where('user_id', Auth::user()->id)->findOrFail($id);
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $group = Group::findOrFail($id);
            } else {
                $group = Group::where('reseller_id', Auth::user()->reseller_id)->findOrFail($id);
            }
        }

        if (!empty($group)) {
            $title = 'Edit Group';
            return view('backend.pages.phonebook.group.edit', compact('title', 'group'));
        } else {
            Session::flash('message', 'Data Not Found!');
            Session::flash('m-class', 'alert-danger');
            return json_encode(['url' => route('phonebook.group.list')]);
        }
    }

    public function groupUpdate(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $this->validate($request, [
            'name' => ['required', Rule::unique('group')->whereNot('id', $id)->where(function ($query) use ($group) {
                return $query->where('user_id', $group->user_id);
            })],
        ]);

        if (isset($request->type)) {
            $type = $request->type;
        } else {
            $type = 'Public';
        }

        $data = [
            'name' => $request->name,
            'type' => $type
        ];

        $group->update($data);
        Session::flash('message', 'Group Updated Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('phonebook.group.list')]);
    }

    public function changeGroupStatus(Request $request, $id)
    {
        $query = Group::find($id);
        $data['status'] = $request->status;
        $query->update($data);
        echo Controller::status_change($id, $request->status);
    }

    public function contactStore(Request $request, $id)
    {
        if (!$request->file) {
            $this->validate($request, [
                'name_en' => 'required',
                'phone' => 'required_without:email',
                'email' => 'required_without:phone',
            ]);
        } else {
            $this->validate($request, [
                'file' => 'file|mimes:txt,csv,xls,xlsx'
            ]);
        }

        if ($request->file) {
            $data = [
                'user_id' => Auth::user()->id,
                'group_id' => $id,
                'reseller_id' => Auth::user()->reseller_id
            ];

            Excel::import(new ContactImport($data), request()->file('file'));
            Session::flash('message', 'Contact Upload Successfully!');
            Session::flash('m-class', 'alert-success');
            return json_encode(['url' => route('phonebook.group.view', $id)]);
        } else {
            $data = [
                'user_id' => Auth::user()->id,
                'group_id' => $id,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_en,
                'phone' => $request->phone,
                'email' => $request->email,
                'profession' => $request->profession,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'division' => $request->division,
                'district' => $request->district,
                'upazilla' => $request->upazilla,
                'blood_group' => $request->blood_group,
                'reseller_id' => Auth::user()->reseller_id
            ];

        }

        Contact::create($data);
        Session::flash('message', 'Contact Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('phonebook.group.view', $id)]);
    }

    public function contacts(Request $request)
    {
        $title = 'Contact List';
        $divisions = allDivision();
        $professions = Contact::whereNotNull('profession')->distinct()->get(['profession']);
        if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
            $query = Contact::with('reseller')->where('user_id', Auth::user()->id);
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $query = Contact::with('reseller');
            } else {
                $query = Contact::with('reseller')->where('reseller_id', Auth::user()->reseller_id);
            }
        }

        $query->select('*', DB::raw('timestampdiff(year, dob, curdate()) as age'));

        $campaign_search_value = [];
        // add or clauses inside a parenthesis group
        $token = $request->common_field;
        if ($token != '') {
            $query = $query->where(function ($query) use ($token) {
                $query->orWhere('name_en', 'like', "%$token%")
                    ->orWhere('name_bn', 'like', "%$token%")
                    ->orWhere('phone', 'like', "$token%")
                    ->orWhere('email', 'like', "%$token%");
            });
            $campaign_search_value[] = array('name_en__like_or' => "%$request->common_field%");
            $campaign_search_value[] = array('name_bn__like_or' => "%$request->common_field%");
            $campaign_search_value[] = array('email__like_or' => "%$request->common_field%");
            $campaign_search_value[] = array('phone__like_or' => "$request->common_field%");
        }

        if ($request->division != '') {
            $query->where('division', $request->division);
            $campaign_search_value[] = array('division__eq' => $request->division);
        }

        if ($request->district != '') {
            $query->where('district', $request->district);
            $campaign_search_value[] = array('district__eq' => $request->district);
        }

        if ($request->upazilla != '') {
            $query->where('upazilla', $request->upazilla);
            $campaign_search_value[] = array('upazilla__eq' => $request->upazilla);
        }

        if ($request->gender != '') {
            $query->where('gender', $request->gender);
            $campaign_search_value[] = array('gender__eq' => $request->gender);
        }

        if ($request->profession != '') {
            $query->where('profession', $request->profession);
            $campaign_search_value[] = array('profession__eq' => $request->profession);
        }

        if ($request->group != '') {
            $query->where('group_id', $request->group);
            $campaign_search_value[] = array('group_id__eq' => $request->group);
        }

        if ($request->age != '') {
            $age_range = explode("-", $request->age);
            $min_age = count($age_range) > 1 ? $age_range[0] : $request->age;
            $max_age = count($age_range) > 1 ? $age_range[1] : $request->age;
            $query->whereBetween(DB::raw('timestampdiff(year, dob, curdate())'), [$min_age, $max_age]);
            $campaign_search_value[] = array('timestampdiff(year, dob, curdate())__between' => [$min_age, $max_age]);
        }

        if ($request->blood_group != '') {
            $query->where('blood_group', $request->blood_group);
            $campaign_search_value[] = array('blood_group' => $request->blood_group);
        }
        $campaign = json_encode($campaign_search_value);

        $tableHeaders = ["check" => "", "sl" => "#", "id" => "Hidden", "name_en" => "Name", "phone" => "Phone Number", "email" => "Email",
            "age" => "Age", "gender" => "Gender", 'blood_group' => 'Blood Group', "division" => "Division", "district" => "District", "upazilla" => "Upazilla", "group" => "Group", "status" => "Status", "action" => "Action"];


        if (empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != config('constants.USER_GROUP')) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 13), ["reseller" => 'Reseller'], array_slice($tableHeaders, 13));
        }

        if ($this->ajaxDatatable()) {
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    return '';
                })
                ->addColumn('sl', function ($row) {
                    return $row['id'];
                })
                ->filterColumn('reseller', function ($query, $keyword) {
                    $query->whereHas('reseller', function ($q) use ($keyword) {
                        $q->where('reseller_name', 'like', '%' . $keyword . '%');
                    });
                })
                ->addColumn('status', function ($row) {
                    $st = ($row['status'] == 'Active') ? 'primary' : 'danger';
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('reseller', function ($row) {
                    $reseller = ($row['reseller_id']) ? $row['reseller']->reseller_name : "-";
                    return $reseller;
                })
                ->addColumn('group', function ($row) {
                    $group = ($row['group_id']) ? $row['group']->name : "-";
                    return $group;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('phonebook.contact.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                    return $btn;
                })
                ->with('campaign', $campaign)
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $ajaxUrl = route('phonebook.contact.list');

        //Campaign Details

        $userBalance = '';
        $resellerBalance = '';
        $user_id = Auth::user()->id;
        if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
            $senderIds = SenderId::whereRaw("find_in_set($user_id, user_id)")->get();
            $domains = Domain::whereRaw("find_in_set($user_id, user_id)")->get();
            $userBalance = UserWallet::where('user_id', Auth::user()->id)->first();
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $senderIds = SenderId::all();
                $domains = Domain::all();
            } else {
                $reseller_id = Auth::user()->reseller_id;
                $senderIds = SenderId::whereRaw("find_in_set($reseller_id, reseller_id)")->get();
                $domains = Domain::whereRaw("find_in_set($user_id, reseller_id)")->get();
                $userBalance = UserWallet::where('user_id', Auth::user()->id)->first();
                $resellerBalance = Reseller::findOrFail(Auth::user()->reseller_id);
            }
        }

        return view('backend.pages.phonebook.contact.list',
            compact('title', 'tableHeaders', 'professions', 'ajaxUrl', 'divisions', 'campaign', 'userBalance', 'resellerBalance', 'senderIds', 'domains')
        );
    }

    public function contactAdd()
    {
        $title = 'Add Contact';
        if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
            $groups = Group::where('user_id', Auth::user()->id)->where('type', 'Public')->get();
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $groups = Group::all();
            } else {
                $groups = Group::where('reseller_id', Auth::user()->reseller_id)->where('type', 'Public')->get();
            }
        }

        $divisions = allDivision();

        return view('backend.pages.phonebook.contact.add', compact('title', 'groups', 'divisions'));
    }

    public function format_gender($gender): string
    {
        if (!$gender || !in_array(strtolower($gender), ["male", "female", "f", "m"])) {
            return "Other";
        } else if (strtolower($gender) == 'm') {
            return "Male";
        } else if (strtolower($gender) == 'f') {
            return "Female";
        } else {
            return $gender;
        }
    }

    public function contactPost(Request $request)
    {
        $this->validate($request, [
            'file' => 'file|mimes:txt,csv,xls,xlsx'
        ]);

        if ($request->file == "") {
            $this->validate($request, [
                'name_en' => 'required',
                'phone' => 'required_without:email',
                'email' => 'required_without:phone',
            ]);
        } else {
            $this->validate($request, [
                'file' => 'file|mimes:txt,csv,xls,xlsx'
            ]);
        }

        if ($request->file) {
            $data = [
                'user_id' => Auth::user()->id,
                'group_id' => $request->group,
                'reseller_id' => Auth::user()->reseller_id
            ];

            if ($request->has('file') and $request->file->getClientOriginalExtension() == 'csv') {
                $file = file(request()->file('file'));

                $chunks = array_chunk($file, 1000);

                $header = [];
                $batch = Bus::batch([])->dispatch();

                foreach ($chunks as $key => $chunk) {
                    $chunkItemsString = array();
                    foreach ($chunk as $index => $item) {
                        $chunkItems = explode(',', trim($item));

                        if ($index == 0 and $key == 0) {
                            $chunkItems[count($chunkItems) + 1] = 'user_id';
                            $chunkItems[count($chunkItems) + 2] = 'group_id';
                            $chunkItems[count($chunkItems) + 3] = 'reseller_id';
                        } else {
                            $chunkItems[count($chunkItems) + 1] = Auth::user()->id;
                            $chunkItems[count($chunkItems) + 2] = $request->group;
                            $chunkItems[count($chunkItems) + 3] = Auth::user()->reseller_id;
                            $chunkItems[5] = $this->format_gender($chunkItems[5]);
                            $chunkItems[6] = date('Y-m-d', strtotime($chunkItems[6]));
                        }

                        $chunkItemsString[] = implode(',', $chunkItems);

                    }

                    $data = array_map('str_getcsv', $chunkItemsString);

                    if ($key == 0) {
                        $header = $data[0];
                        unset($data[0]);
                    }

                    //dd($data);

                    $batch->add(new ContactUploadProcess($data, $header));
                }
            } else if ($request->has('file') and ($request->file->getClientOriginalExtension() == 'xls' || $request->file->getClientOriginalExtension() == 'xlsx')) {
                ini_set('memory_limit', '-1');
                Excel::queueImport(new ContactImport($data), request()->file('file'));
            }

            Session::flash('message', 'Contact Upload Successfully!');
            Session::flash('m-class', 'alert-success');
            return json_encode(['url' => route('phonebook.contact.list')]);
        } else {
            $data = [
                'user_id' => Auth::user()->id,
                'group_id' => $request->group,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_en,
                'phone' => $request->phone,
                'email' => $request->email,
                'profession' => $request->profession,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'division' => $request->division,
                'district' => $request->district,
                'upazilla' => $request->upazilla,
                'blood_group' => $request->blood_group,
                'reseller_id' => Auth::user()->reseller_id
            ];

        }

        Contact::create($data);
        Session::flash('message', 'Contact Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('phonebook.contact.list')]);
    }

    public function contactEdit($id)
    {
        $contact = Contact::where('user_id', Auth::user()->id)->find($id);
        if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
            $groups = Group::where('user_id', Auth::user()->id)->where('type', 'Public')->get();
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $groups = Group::all();
            } else {
                $groups = Group::where('reseller_id', Auth::user()->reseller_id)->where('type', 'Public')->get();
            }
        }
        $title = 'Edit Contact (' . $contact->name_en . ')';
        if (empty($contact)) {
            Session::flash('message', 'Contact Not Found!');
            Session::flash('m-class', 'alert-danger');
            return json_encode(['url' => route('phonebook.contact.list')]);
        }
        $divisions = allDivision();
        if ($contact->division) {
            $division = findHasMany(allDivision(), ['name' => $contact->division]);
            if (count($division) > 0) {
                $districts = findHasMany(allDistrict(), ['division_id' => $division[0]['id']]);
            }
        } else {
            $districts = allDistrict();
        }

        if ($contact->district) {
            $district = findHasMany(allDistrict(), ['name' => $contact->district]);
            if (count($district) > 0) {
                $division = findBelongsTo(allDivision(), ['id' => $district[0]['division_id']]);
                $contact->division = $division['name'];
                $upazillas = findHasMany(allUpazillas(), ['district_id' => $district[0]['id']]);
            }
        } else {
            $upazillas = allUpazillas();
        }

        if ($contact->upazilla) {
            $upazilla = findHasMany(allUpazillas(), ['name' => $contact->upazilla]);
            if (count($upazilla) > 0) {
                $district = findBelongsTo(allDistrict(), ['id' => $upazilla[0]['district_id']]);
                if ($district) {
                    $contact->district = $district['name'];
                    $division = findBelongsTo(allDivision(), ['id' => $district['division_id']]);
                    if ($division) {
                        $contact->division = $division['name'];
                    }
                }
            }
        }

        return view('backend.pages.phonebook.contact.edit', compact('title', 'contact', 'groups', 'divisions', 'districts', 'upazillas'));


    }

    public function contactUpdate(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $this->validate($request, [
            'name_en' => 'required',
            'phone' => 'required_without:email',
            'email' => 'required_without:phone',
        ]);

        $data = [
            'group_id' => $request->group,
            'name_en' => $request->name_en,
            'name_bn' => $request->name_en,
            'phone' => $request->phone,
            'email' => $request->email,
            'profession' => $request->profession,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'division' => $request->division,
            'district' => $request->district,
            'upazilla' => $request->upazilla,
            'blood_group' => $request->blood_group,
        ];

        $contact->update($data);
        Session::flash('message', 'Contact Updated Successfully!');
        Session::flash('m-class', 'alert-success');
        if ($request->group != '')
            return json_encode(['url' => route('phonebook.group.view', $request->group)]);
        return json_encode(['url' => route('phonebook.contact.list')]);
    }

    public function changeContactStatus(Request $request, $id)
    {
        $query = Contact::find($id);
        $data['status'] = $request->status;
        $query->update($data);
        echo Controller::status_change($id, $request->status);
    }

    public function contactDelete(Request $request)
    {
        if ($request->isMethod('post')) {
            $contacts = json_decode($request->data);
            $err_msgs = '';
            foreach ($contacts as $requestContact) {
                $contact = Contact::find($requestContact->id);
                if (!$contact->delete()) {
                    $err_msgs .= 'Can\'t Delete (' . $requestContact->name_en . ')<br>';
                    continue;
                }
            }
            if ($err_msgs != '') {
                Session::flash('message', $err_msgs . '<span class="text-bold-500 text-primary">Other Contact Successfully Deleted</span>');
                Session::flash('m-class', 'alert-danger');

            } else {
                Session::flash('message', 'Contact Successfully Deleted!');
                Session::flash('m-class', 'alert-success');
            }
            return response()->json();
        } else {
            Session::flash('message', 'You Have Not Permission To view this page!');
            Session::flash('m-class', 'alert-danger');
            return response()->json();
        }
    }
}
