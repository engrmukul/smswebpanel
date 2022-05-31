<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reseller;
use App\Models\SenderId;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class SenderIdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Sender Id List';

        if (empty(Auth::user()->reseller_id)) {
            $datas = SenderId::all();
        } else {
            $reseller_id = Auth::user()->reseller_id;
            $datas = SenderId::whereRaw("find_in_set($reseller_id, reseller_id)" )->get();
        }

        $tableHeaders = ["id"=> "#", "senderID"=>"Mask Name", 'username'=>"User", "status"=>"Status", 'action' => 'Manage'];

        if (empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 3), ["reseller"=>'Reseller'], array_slice($tableHeaders, 3));
        }

        $ajaxUrl = route('config.senderid.list');

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
                    $btn =  '<a href="' . route('config.senderid.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.pages.senderid.list', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (empty(Auth::user()->reseler_id)) {
            $title = 'Add Mask Name';
            $resellers = Reseller::all();
            return view('backend.pages.senderid.add', compact('title', 'resellers'));
        } else {
            Session::flash('message', 'You Have Not Permission!');
            Session::flash('m-class', 'alert-danger');
            return json_encode(['url' => route('config.senderid.list')]);
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
            'name' => 'required|max:11|unique:senderid,senderID'
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
            'senderID' => $request->name,
            'reseller_id' => $request->reseller_name
        ];

        SenderId::create($data);
        Session::flash('message', 'Mask Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('config.senderid.list')]);
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
            $title = 'Edit Mask Name';
            $resellers = Reseller::all();
            $edit = SenderId::findOrFail($id);
            return view('backend.pages.senderid.edit', compact('title', 'resellers', 'edit'));
        } else {
            Session::flash('message', 'You Have Not Permission!');
            Session::flash('m-class', 'alert-danger');
            return json_encode(['url' => route('config.senderid.list')]);
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
        $senderId = SenderId::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|max:11|unique:senderid,senderID,'.$senderId->id.''
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
            'senderID' => $request->name,
            'reseller_id' => $request->reseller_name
        ];

        $senderId->update($data);
        Session::flash('message', 'Mask Successfully Edited!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('config.senderid.list')]);
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
        $title = 'Assign Mask';
        return view('backend.pages.senderid.assignmask', compact('title'));
    }

    public function assignUserStore(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'user' => 'required'
        ]);
        $mask = SenderId::findOrFail($request->name);


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
        Session::flash('message', 'Mask Assign Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('config.senderid.list')]);
    }

    public function changeStatus(Request $request, $id)
    {
        $query = SenderId::findOrFail($id);
        $data['status'] = $request->status;
        $query->update($data);
        echo Controller::status_change($id, $request->status);
    }
}
