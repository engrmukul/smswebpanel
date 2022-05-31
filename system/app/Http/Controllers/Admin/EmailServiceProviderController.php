<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class EmailServiceProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Email Service Provider List';
        $datas = EmailServiceProvider::all();
        $tableHeaders = ["id"=> "#", 'name'=>"Email Provider Name", "api_provider"=>"API Provider", "provider_type"=>"Type",
            "tps"=>"TPS", "status"=>"Status", 'action' => 'Manage'];

        $ajaxUrl = route('emailconfig.serviceprovider.list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $st = (ucfirst(strtolower($row['status'])) == 'Active') ? 'primary' : 'danger';
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'. route('emailconfig.serviceprovider.view', $row['id']) .'" class="primary mr-1 ajax-form"><i class="fa fa-eye"></i></a>' .
                        '<a href="' . route('emailconfig.serviceprovider.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.pages.emailconfig.serviceprovider.list', compact('title', 'ajaxUrl', 'tableHeaders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add New Email Service Provider';
        return view('backend.pages.emailconfig.serviceprovider.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:100|unique:email_service_providers',
            'api_provider'=>'required|unique:email_service_providers',
            'provider_type'=>'required',
            'url'=>'required',
            'port'=>'required|numeric',
            'tls'=>'required',
            'username'=>'required',
            'password'=>'required',
            'tps'=>'nullable|numeric'
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;

        if ($request->tps == '') {
            $data['tps'] = 0;
        }

        EmailServiceProvider::create($data);

        Session::flash('message', 'Email Service Provider Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('emailconfig.serviceprovider.list')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $provider = EmailServiceProvider::findOrFail($id);
        $title = 'View Email Service Provider (' . $provider->name .')';
        return view('backend.pages.emailconfig.serviceprovider.view', compact('title', 'provider'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $provider = EmailServiceProvider::findOrFail($id);
        $title = 'Edit Email Service Provider (' . $provider->name .')';
        return view('backend.pages.emailconfig.serviceprovider.edit', compact('title','provider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $provider = EmailServiceProvider::findOrFail($id);
        $this->validate($request,[
            'name'=>'required|max:100|unique:email_service_providers,name,'.$provider->id.'',
            'api_provider'=>'required|unique:email_service_providers,api_provider,'.$provider->id.'',
            'provider_type'=>'required',
            'url'=>'required',
            'port'=>'required|numeric',
            'tls'=>'required',
            'username'=>'required',
            'password'=>'required',
            'tps'=>'nullable|numeric'
        ]);

        $data = $request->all();
        $data['updated_by'] = Auth::user()->id;

        if ($request->tps == '') {
            $data['tps'] = 0;
        }

        $provider->update($data);

        Session::flash('message', 'Email Service Provider Updated Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('emailconfig.serviceprovider.list')]);
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
        $query = EmailServiceProvider::findOrFail($id);
        $data['status'] = $request->status;
        $query->update($data);
        echo Controller::status_change($id, $request->status);
    }
}
