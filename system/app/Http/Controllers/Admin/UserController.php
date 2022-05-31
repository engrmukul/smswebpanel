<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use App\Models\Reseller;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $title = 'User List';
        $user_id = Auth::user()->id;

        $tableHeaders = [
            'id' => "#",
            'user_type' => 'User Type',
            'name' => 'Name',
            'username' => 'User Name',
            'email' => 'Email',
            'mobile' => 'Mobile No',
            'address' => 'Address',
            'dipping' => 'Dipping',
            'sms_rate' => 'SMS Rate',
            'tps' => 'TPS',
            'status' => 'Status',
            'created_by' => 'Created By',
            'action' => 'Manage'
        ];

        if(config('constants.EMAIL_SERVICE')==true) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 9), [
                'email_rate' => 'Email Rate'], array_slice($tableHeaders, 9));
        }

        if ($this->ajaxDatatable()) {
            
            if (empty(Auth::user()->reseller_id)) {
                $users = User::whereRaw("find_in_set($user_id, assign_user_id)" );
            } else {
                $users = User::where('reseller_id', Auth::user()->reseller_id)->whereRaw("find_in_set($user_id, assign_user_id)" );
            }
            $users  = $users->orderBy('status');

            return $this->userList($users);
        }

        $ajaxUrl = route('user.list');

        return view('backend.pages.user.list', compact( 'title', 'tableHeaders', 'ajaxUrl'));
    }

    public function activeUsers(Request $request)
    {
        $title = 'Active User List';
        $user_id = Auth::user()->id;
        

        $tableHeaders = [
            'id' => "#",
            'user_type' => 'User Type',
            'name' => 'Name',
            'username' => 'User Name',
            'email' => 'Email',
            'mobile' => 'Mobile No',
            'address' => 'Address',
            'dipping' => 'Dipping',
            'sms_rate' => 'SMS Rate',
            'tps' => 'TPS',
            'status' => 'Status',
            'created_by' => 'Created By',
            'action' => 'Manage'
        ];

        if(config('constants.EMAIL_SERVICE')==true) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 9), [
                'email_rate' => 'Email Rate'], array_slice($tableHeaders, 9));
        }

        if ($this->ajaxDatatable()) {
            if (empty(Auth::user()->reseller_id)) {
                $users = User::whereRaw("find_in_set($user_id, assign_user_id)" )->where('status', 'Active');
            } else {
                $users = User::where('reseller_id', Auth::user()->reseller_id)->where('status', 'Active')->whereRaw("find_in_set($user_id, assign_user_id)" );
            }
            $users  = $users->orderBy('name');
            return $this->userList($users);
        }

        $ajaxUrl = route('user.list.active');

        return view('backend.pages.user.list', compact( 'title', 'tableHeaders', 'ajaxUrl'));
    }

    public function inactiveUsers(Request $request)
    {
        $title = 'Inactive User List';
        $user_id = Auth::user()->id;

        $tableHeaders = [
            'id' => "#",
            'user_type' => 'User Type',
            'name' => 'Name',
            'username' => 'User Name',
            'email' => 'Email',
            'mobile' => 'Mobile No',
            'address' => 'Address',
            'dipping' => 'Dipping',
            'sms_rate' => 'SMS Rate',
            'tps' => 'TPS',
            'status' => 'Status',
            'created_by' => 'Created By',
            'action' => 'Manage'
        ];

        if(config('constants.EMAIL_SERVICE')==true) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 9), [
                'email_rate' => 'Email Rate'], array_slice($tableHeaders, 9));
        }

        if ($this->ajaxDatatable()) {
            if (empty(Auth::user()->reseller_id)) {
                $users = User::whereRaw("find_in_set($user_id, assign_user_id)" )->where('status', '!=','Active');
            } else {
                $users = User::where('reseller_id', Auth::user()->reseller_id)->where('status', '!=', 'Active')->whereRaw("find_in_set($user_id, assign_user_id)" );
            }
            $users  = $users->orderBy('name');
            return $this->userList($users);
        }

        $ajaxUrl = route('user.list.inactive');

        return view('backend.pages.user.list', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add New User';

        $resellers = Reseller::all();
        if (empty(Auth::user()->reseller_id)) {
            if (Auth::user()->id_user_group != 1) {
                if (Auth::user()->id_user_group != 2) {
                    $user_groups = UserGroup::whereIn('id', [3,4])->get();
                    $users = '';
                } else {
                    $user_groups = UserGroup::whereNotIn('id', [1, 2])->get();
                    $users = User::whereNotIn('id_user_group', [1,2,4])->get();
                }

            } else {
                $user_groups = UserGroup::get();
                $users = User::whereNotIn('id_user_group', [1,2,4])->get();
            }

        } else {
            if (Auth::user()->id_user_group != 3) {
                $user_groups = UserGroup::where('id', 4)->get();
                $users = '';
            } else {
                $user_groups = UserGroup::whereNotIn('id',[1,2,3])->get();
                $users = User::whereNotIn('id_user_group', [1,2,3,4])->get();
            }
        }

        $smsRates = Rate::where('rate_type', 'sms')->where('reseller_id', Auth::user()->reseller_id)->get();
        $emailRates = Rate::where('rate_type', 'email')->where('reseller_id', Auth::user()->reseller_id)->get();


        return view('backend.pages.user.add', compact('resellers', 'title', 'user_groups', 'users', 'smsRates', 'emailRates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'name'=>'required|max:100',
            'username'=>'required|max:50|unique:user',
            'email'=>'required|unique:user',
            'password'=>'required|min:4|confirmed',
            'mobile'=>'numeric|required',
            'address'=>'required',
            'sms_rate' => 'required_if:user_type,4',
            'email_rate' => config('constants.EMAIL_SERVICE')==true? 'required_if:user_type,4': 'nullable',
            'user_type'=>'required',
            'reseller_name'=>'required_if:user_type,3',
            'tps'=>'nullable|numeric'
        ]);

        $user_ids = array();
        $controller_id = Auth::user()->id;

        $users = User::whereIn('id_user_group', [1,2])->select('id')->get();

        foreach ($users as $user) {
            $user_ids[] = $user->id;
        }

        $tps = $request->tps;
        if ($request->tps == '') {
            $tps = 0;
        }

        if (empty(Auth::user()->reseller_id)) {
            if (Auth::user()->id_user_group != 1 && Auth::user()->id_user_group != 2) {
                $user_ids[] = $controller_id;
            }
            if ($request->user_type == 3){
                $reseller_id = $request->reseller_name;
            } else {
                $reseller_id = null;
            }
        } else {

            $rUsers = User::where('id_user_group', 3)->where('reseller_id', Auth::user()->reseller_id)->select('id')->get();
            foreach ($rUsers as $rUser) {
                $user_ids[] = $rUser->id;
            }
            if(Auth::user()->id_user_group != 3){
                $user_ids[] = $controller_id;
            }
            $reseller_id = Auth::user()->reseller_id;
            $reseller = Reseller::findOrFail($reseller_id);
            if ($tps > $reseller->tps) {
                $msg = 'You can give Max '. $reseller->tps .' tps to your client';
                return response()->json(array(
                    'errors' => [
                        'tps' => [$msg]
                    ]
                ), 400);
            }
        }

        $assign_id =array_merge((array)$user_ids, (array) $request->assign_user);

        $request->merge([
            'assign_user_id' => implode(',', $assign_id)
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'mobile' => $request->mobile,
            'address' => $request->address,
            'id_user_group' => $request->user_type,
            'created_by' => $controller_id,
            'reseller_id' => $reseller_id,
            'assign_user_id' => $request->assign_user_id,
            'status' => 'PENDING',
            'sms_rate_id' => $request->sms_rate,
            'email_rate_id' => $request->email_rate,
            'billing_type' => ($request->billing_type == '' or $request->billing_type == null)?'prepaid':$request->billing_type,
            'tps' => $tps,
            'dipping' => $request->dipping
        ];
	//print_r($data);exit;

        $new_user_id = User::create($data)->id;

        if ($request->user_type == 4  or $reseller_id != null) {
            $walletData = [
                'user_id' => $new_user_id
            ];
            UserWallet::create($walletData);
        }

        Session::flash('message', 'User Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('user.list.inactive')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = Auth::user()->id;
        if (empty(Auth::user()->reseller_id)) {
            $user = User::with('reseller')->whereRaw("find_in_set($user_id, assign_user_id)" )->where('id', $id)->first();
        } else {
            $user = User::where('reseller_id', Auth::user()->reseller_id)->whereRaw("find_in_set($user_id, assign_user_id)" )->where('id', $id)->first();
        }
        $title = 'View User (' . $user->name. ')';

        $assign_users = User::select('name')->whereIn('id', explode(',', $user->assign_user_id))->get();
        foreach ($assign_users as $assign_user) {
            $assign_user_name[] = $assign_user->name;
        }

        return view('backend.pages.user.view', compact('user', 'title', 'assign_user_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_id = Auth::user()->id;
        $user = User::whereRaw("find_in_set($user_id, assign_user_id)" )->where('id', $id)->first();
        if(!$user){
            Session::flash('message', 'User Not Found!');
            Session::flash('m-class', 'alert-danger');
            return json_encode(['url' => route('user.list')]);
        }
        $title = 'Edit User (' . $user->name . ')';
        $resellers = Reseller::all();
        if (empty(Auth::user()->reseller_id)) {
            if (Auth::user()->id_user_group != 1) {
                if (Auth::user()->id_user_group != 2) {
                    $user_groups = UserGroup::whereIn('id', [3,4])->get();
                    $assignUsers = '';
                } else {
                    $user_groups = UserGroup::whereNotIn('id', [1, 2])->get();
                    $assignUsers = User::whereNotIn('id_user_group', [1,2,4])->get();
                }

            } else {
                $user_groups = UserGroup::get();
                $assignUsers = User::whereNotIn('id_user_group', [1,2,4])->get();
            }

        } else {
            if (Auth::user()->id_user_group != 3) {
                $user_groups = UserGroup::where('id', 4)->get();
                $assignUsers = '';
            } else {
                $user_groups = UserGroup::whereNotIn('id',[1,2,3])->get();
                $assignUsers = User::whereNotIn('id_user_group', [1,2,3,4])->get();
            }
        }

        $smsRates = Rate::where('rate_type', 'sms')->where('reseller_id', Auth::user()->reseller_id)->get();
        $emailRates = Rate::where('rate_type', 'email')->where('reseller_id', Auth::user()->reseller_id)->get();

        return view('backend.pages.user.edit', compact('resellers', 'title', 'user_groups', 'assignUsers', 'smsRates', 'emailRates', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $thisUser = User::findOrFail($id);
        $this->validate($request,[
            'name'=>'required|max:100',
            'username'=>'required|max:50|unique:user,username,'.$thisUser->id.'',
            'email'=>'required|unique:user,email,'.$thisUser->id.'',
            'mobile'=>'numeric|required',
            'address'=>'required',
            'user_type'=>'required',
            'sms_rate' => 'required_if:user_type,4',
            'email_rate' => config('constants.EMAIL_SERVICE')==true? 'required_if:user_type,4': 'nullable',
            'billing_type' => 'required_if:user_type,4',
            'reseller_name'=>'required_if:user_type,3',
            'tps'=>'nullable|numeric'
        ]);

        $user_ids = array();
        $controller_id = Auth::user()->id;

        $users = User::whereIn('id_user_group', [1,2])->select('id')->get();

        foreach ($users as $user) {
            $user_ids[] = $user->id;
        }

        $tps = $request->tps;
        if ($request->tps == '') {
            $tps = 0;
        }

        if (empty(Auth::user()->reseller_id)) {
            if (Auth::user()->id_user_group != 1 && Auth::user()->id_user_group != 2) {
                $user_ids[] = $controller_id;
            }

            if ($request->user_type != 1 && $request->user_type != 2){
                $reseller_id = $request->reseller_name;

            } else {
                $reseller_id = null;
            }
        } else {

            $rUsers = User::where('id_user_group', 3)->where('reseller_id', Auth::user()->reseller_id)->select('id')->get();

            foreach ($rUsers as $rUser) {
                $user_ids[] = $rUser->id;
            }
            if(Auth::user()->id_user_group != 3){
                $user_ids[] = $controller_id;
            }

            $reseller_id = Auth::user()->reseller_id;
            $reseller = Reseller::findOrFail($reseller_id);
            if ($tps > $reseller->tps) {
                $msg = 'You can give Max '. $reseller->tps .' tps to your client';
                return response()->json(array(
                    'errors' => [
                        'tps' => [$msg]
                    ]
                ), 400);
            }
        }

        $assign_id =array_merge((array)$user_ids, (array) $request->assign_user);

        $request->merge([
            'assign_user_id' => implode(',', $assign_id)
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'id_user_group' => $request->user_type,
            'created_by' => $controller_id,
            'reseller_id' => $reseller_id,
            'assign_user_id' => $request->assign_user_id,
            'sms_rate_id' => $request->sms_rate,
            'email_rate_id' => $request->email_rate,
            'billing_type' => ($request->billing_type == '' or $request->billing_type == null)?'prepaid':$request->billing_type,
            'tps'   => $tps
        ];


        if ($request->user_type == 4  or $reseller_id != null) {
            $userWallet = UserWallet::where('user_id', $id)->first();
            if (!$userWallet){
                $walletData = [
                    'user_id' => $id
                ];
                UserWallet::create($walletData);
            }
        } else {
            $userWallet = UserWallet::where('user_id', $id)->first();
            if ($userWallet){
                $userWallet->delete();
            }
        }

        $thisUser->update($data);

        Session::flash('message', 'User Updated Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('user.list.active')]);
    }

    public function updatePassword(Request $request, $id) {
        if ($request->isMethod('post')) {
            $user = User::findOrFail($id);
            $request->validate([
                'new_password' => 'required|confirmed'
            ]);

            $inputs = [
                'password' => bcrypt($request->new_password)
            ];

            $user->update($inputs);
            Session::flash('message', 'Password Changed Successfully!');
            Session::flash('m-class', 'alert-success');
            return json_encode(['url' => route('user.list.active')]);
        } else {
            Session::flash('message', 'You Have Not Permission To View This Page!');
            Session::flash('m-class', 'alert-danger');
            return json_encode(['url' => route('user.list')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeStatus(Request $request, $id) {
        $user = User::findOrFail($id);
        $data['status'] = $request->status;
        $user->update($data);
        echo Controller::status_change($id, $request->status);
    }

    public function changeDeeping(Request $request, $id) {
        $user = User::findOrFail($id);
        $data['dipping'] = $request->status;
        $user->update($data);
        echo Controller::status_change($id, $request->status);
    }

    public function profileEdit($id) {
        if($id == Auth::user()->id) {
            $title = 'Update Profile';
            $user = User::findOrFail($id);
            return view('backend.pages.user.profileUpdate', compact('title', 'user'));
        } else {
            Session::flash('message', 'You can not edit this account!');
            Session::flash('m-class', 'alert-danger');
            return json_encode(['url' => route('dashboard')]);
        }
    }

    public function profileEditStore(Request $request, $id) {
        if($id == Auth::user()->id) {
            $user = User::findOrFail($id);
            $this->validate($request,[
                'name'=>'required|max:100',
                'mobile'=>'numeric|required',
                'address'=>'required'
            ]);

            $inputs = [
                'name'=> $request->name,
                'mobile'=> $request->mobile,
                'address'=> $request->address
            ];
            if (request()->hasFile('image')) {
                $file = request()->file('image');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $path = 'assets/images/users';
                File::delete('assets/images/users/' . $user->photo);
                $file->move($path, $fileName);
                $inputs['photo'] = $fileName;
            }

            $user->update($inputs);
            Session::flash('message', 'Profile Updated Successfully!');
            Session::flash('m-class', 'alert-success');
            return json_encode(['url' => route('dashboard')]);
        } else {
            Session::flash('message', 'You can not edit this account!');
            Session::flash('m-class', 'alert-danger');
            return json_encode(['url' => route('dashboard')]);
        }
    }

    public function passwordChange(Request $request, $id) {
        if($id == Auth::user()->id) {
            $user = User::findOrFail($id);
            $request->validate([
                'new_password' => 'required_with:old_password|confirmed',
                'old_password' => 'required_with:new_password'
            ]);

            if (!\Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->withErrors([
                    'old_password' => 'The current password is incorrect.'
                ]);
            }
            $inputs = [
                'password' => bcrypt($request->new_password)
            ];

            $user->update($inputs);
            Session::flash('message', 'Password Changed Successfully!');
            Session::flash('m-class', 'alert-success');
            return json_encode(['url' => route('dashboard')]);
        } else {
            Session::flash('message', 'You can not change password for this account!');
            Session::flash('m-class', 'alert-danger');
            return json_encode(['url' => route('dashboard')]);
        }
    }

    public function loginAs()
    {
        //get the id from the post
        $id = request('user_id');

        //if session exists remove it and return login to original user
        if (session()->has('hasClonedUser')) {
            auth()->loginUsingId(session()->get('hasClonedUser'), true);
            session()->remove('hasClonedUser');
            return redirect()->route('user.list.active');
        }

        //only run for developer, clone selected user and create a cloned session

        session()->put('hasClonedUser', auth()->user()->id);
        auth()->loginUsingId($id);
        return redirect()->route('dashboard');

    }

    function userList($datas) {
        return DataTables::of($datas)
            ->addColumn('status', function ($row) {
                $st = ($row['status'] == 'ACTIVE') ? 'primary' : (($row['status'] == 'INACTIVE') ? 'danger' : 'warning');
                $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-type="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                return $status_btn;
            })
            ->addColumn('dipping', function ($row) {
                $st = ($row['dipping'] == 'Active') ? 'primary' : 'danger';
                $d_btn = '<span class="cursor-pointer text-center dipping_change_id_' . $row['id'] . ' ' . $st . '" id="dipping" data-type="dipping" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['dipping'])) . '</span>';
                return $d_btn;
            })
            ->addColumn('created_by', function ($row){
                return ($row['created_by'] != 0)?$row['createBy']->name: 'Self Sign UP';
            })
            ->addColumn('user_type', function ($row){
                return $row['userType']? $row['userType']->title: '';
            })
            ->addColumn('reseller', function ($row){
                return ($row['reseller'])?$row['reseller']->reseller_name: '-';
            })
            ->addColumn('sms_rate', function ($row){
                return ($row['smsRate'])?$row['smsRate']->rate_name:'';
            })
            ->addColumn('email_rate', function ($row){
                return ($row['emailRate'])?$row['emailRate']->rate_name:'';
            })
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('user.view', $row['id']).'" class="primary mr-1 ajax-form"><i class="fa fa-eye"></i></a>
                    <a href="'. route('user.edit', $row['id']) .'" class="warning mr-1"><i class="fa fa-pencil"></i></a>';
                if($row['id_user_group'] != 1) {
                    $fvr = '\''.$row["name"].'\', \''. route('login.asuser') .'\', \''.$row["id"].'\'';
                    $logasbtn = '<a href = "" onclick = "return myFunction('. $fvr .')" class="secondary" ><i class="icon-login" ></i ></a >
                        <form id = "cloneuser'.$row['id'].'" action = "" method = "post" >
                            '.csrf_field().'
                            <input type = "hidden" name = "user_id" value = "'.$row['id'].'" >
                        </form >';
                } else {
                    $logasbtn = '';
                }
                return '<span style="display: flex">'. $btn.$logasbtn .'</span>';
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'dipping', 'action'])
            ->make(true);
    }
}
