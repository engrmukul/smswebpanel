<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailRoute;
use App\Models\EmailServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class EmailRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|mixed
     */
    public function index()
    {
        $title = 'Channel List';
        $datas = EmailRoute::query();
        $tableHeaders = ["id"=> "#", 'username'=>"User", "provider_name"=>"Service Provider",
            "source_domain"=>"Source Domain", "status"=>"Status", 'action' => 'Manage'];

        $ajaxUrl = route('emailconfig.route.list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('username', function($row){
                    return ($row->user)?$row->user->name:'-';
                })
                ->addColumn('provider_name', function($row) {
                    return $row->emailServiceProvider->name;
                })
                ->addColumn('status', function($row){
                    $st = (ucfirst(strtolower($row['status'])) == 'Active') ? 'primary' : 'danger';
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('action', function($row){
                    $btn =  '<a href="' . route('emailconfig.route.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.pages.emailconfig.route.list', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response|mixed
     */
    public function create()
    {
        $title = 'Add New Eamil Route';
        $users = User::all();
        $providers = EmailServiceProvider::all();
        return view('backend.pages.emailconfig.route.add', compact('title', 'users', 'providers'));
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
            'provider'=>'required',
            'source_domain' => 'required'
        ]);

        $data = $request->all();
        $data['email_service_provider_id'] = $request->provider;
        $data['user_id'] = $request->user;

        EmailRoute::create($data);
        Session::flash('message', 'Route Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('emailconfig.route.list')]);
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
        $route = EmailRoute::findOrFail($id);
        $title = 'Edit Email Route';
        $users = User::all();
        $providers = EmailServiceProvider::all();
        return view('backend.pages.emailconfig.route.edit', compact('title', 'users', 'providers', 'route'));
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
        $route = EmailRoute::findOrFail($id);
        $this->validate($request,[
            // 'operator'=>'required',
            'provider'=>'required',
            'source_domain' => 'required'
        ]);

        $data = $request->all();
        // $data['operrator_prefix'] = implode(', ', $_POST['operrator_prefix']);
        $data['email_service_provider_id'] = $request->provider;
        $data['user_id'] = $request->user;

        $route->update($data);
        Session::flash('message', 'Route Updated Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('emailconfig.route.list')]);
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
        $query = EmailRoute::findOrFail($id);
        $data['status'] = $request->status;
        $query->update($data);
        echo Controller::status_change($id, $request->status);
    }
}
