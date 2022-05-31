<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class SmsServiceProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Channel List';
        $datas = Channel::query();
        $tableHeaders = ["id"=> "#", 'name'=>"SMS Provider Name", "api_provider"=>"API Provider", "channel_type"=>"Type",
            "method"=>"Method", "tps"=>"TPS", "status"=>"Status", 'action' => 'Manage'];

        $ajaxUrl = route('smsconfig.serviceprovider.list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $st = (ucfirst(strtolower($row['status'])) == 'Active') ? 'primary' : 'danger';
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'. route('smsconfig.serviceprovider.view', $row['id']) .'" class="primary mr-1 ajax-form"><i class="fa fa-eye"></i></a>' .
                            '<a href="' . route('smsconfig.serviceprovider.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.pages.smsconfig.serviceprovider.list', compact('title', 'ajaxUrl', 'tableHeaders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add New Channel';
        $operators = Operator::all();
        return view('backend.pages.smsconfig.serviceprovider.add', compact('title', 'operators'));
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
            'name'=>'required|max:100|unique:channel',
            'api_provider'=>'required',
            'channel_type'=>'required',
            'username'=>'required',
            'password'=>'required',
            'method'=>'required_if:channel_type,==,HTTP|nullable',
            'content_type'=>'required_if:method,==,POST|nullable',
            'url'=>'required_if:channel_type,==,HTTP|nullable',
            'ip'=>'required_if:channel_type,==,SMPP|ip|nullable',
            'port'=>'required_if:channel_type,==,SMPP|nullable',
            'tps'=>'nullable|numeric'
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;

        if ($request->tps == '') {
            $data['tps'] = 0;
        }

        Channel::create($data);

        Session::flash('message', 'Channel Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('smsconfig.serviceprovider.list')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $provider = Channel::findOrFail($id);
        $title = 'View Channel (' . $provider->name .')';
        return view('backend.pages.smsconfig.serviceprovider.view', compact('title', 'provider'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $provider = Channel::findOrFail($id);
        $title = 'Add New Channel (' . $provider->name .')';
        $operators = Operator::all();
        return view('backend.pages.smsconfig.serviceprovider.edit', compact('title', 'operators', 'provider'));
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
        $provider = Channel::findOrFail($id);
        $this->validate($request,[
            'name'=>'required|max:100|unique:channel,name,'.$provider->id.'',
            'api_provider'=>'required',
            'channel_type'=>'required',
            'username'=>'required',
            'password'=>'required',
            'method'=>'required_if:channel_type,==,HTTP|nullable',
            'content_type'=>'required_if:method,==,POST|nullable',
            'url'=>'required_if:channel_type,==,HTTP|nullable',
            'ip'=>'required_if:channel_type,==,SMPP|ip|nullable',
            'port'=>'required_if:channel_type,==,SMPP|nullable',
            'tps'=>'nullable|numeric'
        ]);

        $data = $request->all();
        $data['updated_by'] = Auth::user()->id;

        if ($request->tps == '') {
            $data['tps'] = 0;
        }

        $provider->update($data);

        Session::flash('message', 'Channel Updated Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('smsconfig.serviceprovider.list')]);
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
        $query = Channel::findOrFail($id);
        $data['status'] = $request->status;
        $query->update($data);
        echo Controller::status_change($id, $request->status);
    }
}
