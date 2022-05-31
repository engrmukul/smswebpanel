<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Operator;
use App\Models\Route;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class SmsRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|mixed
     */
    public function index()
    {
        $title = 'Sms Route List';
        $datas = Route::query();
        $tableHeaders = ["id"=> "#", 'username'=>"User", "operator_prefix"=>"Operator Prefix", "provider_name"=>"Service Provider",
            "mask"=>"Mask Option", "default_mask"=>"Default Mask", "status"=>"Status", 'action' => 'Manage'];

        $ajaxUrl = route('smsconfig.route.list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('username', function($row){
                    return ($row->user)?$row->user->name:'-';
                })
                ->addColumn('operator_prefix', function($row){
                    return is_array($row->operator_prefix)? implode(",", $row->operator_prefix) : '';
                })
                ->addColumn('provider_name', function($row) {
                    return $row['channel']->name;
                })
                ->addColumn('mask', function($row) {
                    return ($row->has_mask == 2)? 'ALL' : (($row->has_mask == 1)? 'YES':'NO');
                })
                ->addColumn('status', function($row){
                    $st = (ucfirst(strtolower($row['status'])) == 'Active') ? 'primary' : 'danger';
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('action', function($row){
                    $btn =  '<a href="' . route('smsconfig.route.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.pages.smsconfig.route.list', compact('title', 'ajaxUrl', 'tableHeaders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response|mixed
     */
    public function create()
    {
        $title = 'Add New Route';
        $users = User::all();
        $operators = Operator::all();
        $channels = Channel::all();
        return view('backend.pages.smsconfig.route.add', compact('title', 'users', 'operators', 'channels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|mixed
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'channel'=>'required',
            'has_mask'=>'required'
        ]);

        $data = $request->all();
        $data['channel_id'] = $request->channel;
        $data['user_id'] = $request->user;

        Route::create($data);
        Session::flash('message', 'Route Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('smsconfig.route.list')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response|mixed
     */
    public function edit($id)
    {
        $route = Route::findOrFail($id);
        $title = 'Edit Route';
        $users = User::all();
        $operators = Operator::all();
        $channels = Channel::all();
        return view('backend.pages.smsconfig.route.edit', compact('title', 'users', 'operators', 'channels', 'route'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response|mixed
     */
    public function update(Request $request, $id)
    {
        $route = Route::findOrFail($id);
        $this->validate($request,[
            'channel'=>'required',
            'has_mask'=>'required'
        ]);

        $data = $request->all();
        // $data['operrator_prefix'] = implode(', ', $_POST['operrator_prefix']);
        $data['channel_id'] = $request->channel;
        $data['user_id'] = $request->user;

        $route->update($data);
        Session::flash('message', 'Route Updated Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('smsconfig.route.list')]);
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
        $query = Route::findOrFail($id);
        $data['status'] = $request->status;
        $query->update($data);
        echo Controller::status_change($id, $request->status);
    }
}
