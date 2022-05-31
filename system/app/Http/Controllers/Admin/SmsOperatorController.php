<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SmsOperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Operator List';
        $datas = Operator::query();
        $tableHeaders = ["id"=> "#", 'full_name'=>"Operator Name", "short_name"=>"Short Name", "prefix"=>"Prefix",
            "status"=>"Status", "ton"=>"TON", "npi"=>"NPI", 'action' => 'Manage'];

        $ajaxUrl = route('smsconfig.operator.list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $st = (ucfirst(strtolower($row['status'])) == 'Active') ? 'primary' : 'danger';
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="' . route('smsconfig.operator.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.pages.smsconfig.operator.list', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Operator Add';
        $countries = Country::all();
        return view('backend.pages.smsconfig.operator.add', compact('title', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name'=>'string|required|max:50|unique:operator',
            'short_name'=>'string|required|unique:operator',
            'prefix'=>'numeric|required',
            'country'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $data = $request->all();
        $data['country_id'] = $request->country;
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;

        Operator::create($data);
        Session::flash('message', 'Operator Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('smsconfig.operator.list')]);
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $operator = Operator::findOrFail($id);
        $title = 'Edit Operator ('. $operator->full_name .')';
        $countries = Country::all();
        return view('backend.pages.smsconfig.operator.edit', compact('title', 'countries', 'operator'));
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
        $operator = Operator::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'full_name'=>'string|required|max:50|unique:operator,full_name,'.$operator->id.'',
            'short_name'=>'string|required|unique:operator,short_name,'.$operator->id.'',
            'prefix'=>'numeric|required',
            'country'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $data = $request->all();
        $data['country_id'] = $request->country;
        $data['updated_by'] = Auth::user()->id;

        $operator->update($data);
        Session::flash('message', 'Operator Updated Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('smsconfig.operator.list')]);
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
        $query = Operator::findOrFail($id);
        $data['status'] = $request->status;
        $query->update($data);
        echo Controller::status_change($id, $request->status);
    }
}
