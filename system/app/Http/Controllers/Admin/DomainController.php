<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reseller;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class DomainController extends Controller
{
    public function index()
    {
        $title = 'Domain List';

        if (empty(Auth::user()->reseller_id)) {
            $datas = Domain::query();
        } else {
            $reseller_id = Auth::user()->reseller_id;
            $datas = Domain::whereRaw("find_in_set($reseller_id, reseller_id)" );
        }

        $tableHeaders = ["id"=> "#", "domain"=>"Domain Name", 'username'=>"User", "status"=>"Status", 'action' => 'Manage'];

        if (empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 3), ["reseller"=>'Reseller'], array_slice($tableHeaders, 3));
        }

        $ajaxUrl = route('config.domain.list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('username', function($row){
                        $users = User::whereIn('id', explode(',', $row->user_id))->get();
                        foreach ($users as $user) {
                            $assign_users[] = $user->name;
                        }
                    return $assign_users?implode(', ', $assign_users):'-';
                })
                ->addColumn('reseller', function($row){
                    return ($row->reseller_id)?$row->reseller->reseller_name:'-';
                })
                ->addColumn('status', function($row){
                    $st = (ucfirst(strtolower($row['status'])) == 'Active') ? 'primary' : 'danger';
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('action', function($row){
                    $btn =  '<a href="' . route('config.domain.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.pages.domain.list', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (empty(Auth::user()->reseler_id)) {
            $title = 'Add Domain Name';
            $resellers = Reseller::all();
            return view('backend.pages.domain.add', compact('title', 'resellers'));
        } else {
            Session::flash('message', 'You Have Not Permission!');
            Session::flash('m-class', 'alert-danger');
            return redirect()->route('config.domain.list');
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:domains,domain'
        ]);



        $users = User::whereIn('id_user_group', [1,2])->select('id')->get();
        foreach ($users as $user) {
            $user_ids[] = $user->id;
        }

        if ($request->reseller_name != '') {
            $resellersUsers = User::where('reseller_id', $request->reseller_name)->where('id_user_group', 3)->select('id')->get();
            foreach ($resellersUsers as $resellersUser) {
                $user_ids[] = $resellersUser->id;
            }
        }

        $request->merge([
            'user_id' => implode(',', (array)$user_ids)
        ]);

        $data = [
            'user_id' => $request->user_id,
            'domain' => $request->name,
            'from_email' => 'no-reply@'.$request->name,
            'reseller_id' => $request->reseller_name
        ];

        Domain::create($data);
        Session::flash('message', 'Domain Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('config.domain.list')]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (empty(Auth::user()->reseler_id)) {
            $title = 'Edit Domain Name';
            $resellers = Reseller::all();
            $edit = Domain::findOrFail($id);
            return view('backend.pages.domain.edit', compact('title', 'resellers', 'edit'));
        } else {
            Session::flash('message', 'You Have Not Permission!');
            Session::flash('m-class', 'alert-danger');
            return json_encode(['url' => route('config.domain.list')]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $domain = Domain::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|unique:domains,domain,'.$domain->id.''
        ]);



        $users = User::whereIn('id_user_group', [1,2])->select('id')->get();
        foreach ($users as $user) {
            $user_ids[] = $user->id;
        }

        if ($request->reseller_name != '') {
            $resellersUsers = User::where('reseller_id', $request->reseller_name)->where('id_user_group', 3)->select('id')->get();
            foreach ($resellersUsers as $resellersUser) {
                $user_ids[] = $resellersUser->id;
            }
        }

        $request->merge([
            'user_id' => implode(',', (array)$user_ids)
        ]);

        $data = [
            'user_id' => $request->user_id,
            'domain' => $request->name,
            'from_email' => 'no-reply@'.$request->name,
            'reseller_id' => $request->reseller_name
        ];

        $domain->update($data);
        Session::flash('message', 'Domain Successfully Edited!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('config.domain.list')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function assignUser() {
        $title = 'Assign Domain';
        return view('backend.pages.domain.assigndomain', compact('title',));
    }

    public function assignUserStore(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'user' => 'required'
        ]);
        $mask = Domain::findOrFail($request->name);


        $users = User::whereIn('id_user_group', [1,2])->select('id')->get();
        foreach ($users as $user) {
            $user_ids[] = $user->id;
        }

        if ($mask->reseller_id) {
            $resellersUsers = User::where('reseller_id', $mask->reseller->id)->where('id_user_group', 3)->select('id')->get();
            foreach ($resellersUsers as $resellersUser) {
                $user_ids[] = $resellersUser->id;
            }
        }

        $assign_id =array_merge((array)$user_ids, (array) $request->user);

        $request->merge([
            'user_id' => implode(',', $assign_id)
        ]);

        $data = [
            'user_id' => $request->user_id
        ];

        $mask->update($data);
        Session::flash('message', 'Domain Assign Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('config.domain.list')]);
    }

    public function changeStatus(Request $request, $id)
    {
        $query = Domain::findOrFail($id);
        $data['status'] = $request->status;
        $query->update($data);
        echo Controller::status_change($id, $request->status);
    }
}
