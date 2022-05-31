<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'SMS Rate List';
        if (empty(Auth::user()->reseller_id)) {
            $rates = Rate::where('rate_type', 'sms');
            
        } else {
            $rates = Rate::where('rate_type', 'sms')->where('reseller_id', Auth::user()->reseller_id)->where('id', '!=', Auth::user()->rate_id);
        }

        $tableHeaders = ['id' => "#", 'rate_name' => 'Rate Name', 'selling_masking_rate' => 'Masking Rate', 'selling_nonmasking_rate' => 'Non Masking Rate', 'action' => 'Manage'];

        if($this->ajaxDatatable()) {
            return DataTables::of($rates)
                ->addColumn('action', function ($row) {
                    return '<a href="'. route('rate.edit', $row['id']) .'" class="warning mr-1"><i class="fa fa-pencil"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $ajaxUrl = route('rate.list');

        return view('backend.pages.rate.list', compact('rates', 'title', 'tableHeaders', 'ajaxUrl'));
    }

    public function emailRate()
    {
        $title = 'Email Rate List';
        if (empty(Auth::user()->reseller_id)) {
            $rates = Rate::where('rate_type', 'email');
        } else {
            $rates = Rate::where('rate_type', 'email')->where('reseller_id', Auth::user()->reselelr_id)->where('id', '!=', Auth::user()->rate_id);
        }

        $tableHeaders = ['id' => "#", 'rate_name' => 'Rate Name', 'email_rate' => 'Email Rate', 'action' => 'Manage'];

        if($this->ajaxDatatable()) {
            return DataTables::of($rates)
                ->addColumn('action', function ($row) {
                    return '<a href="'. route('rate.edit', $row['id']) .'" class="warning mr-1"><i class="fa fa-pencil"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $ajaxUrl = route('rate.list.email');

        return view('backend.pages.rate.list', compact('rates', 'title', 'tableHeaders', 'ajaxUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add New Rate';
        return view('backend.pages.rate.add', compact('title'));
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
            'rate_name' => ['required', Rule::unique('rates')->where(function ($query) {
                return $query->where('reseller_id', Auth::user()->reseller_id);
            })],
            'rate_type'=>'required',
            'selling_masking_rate'=>'required_if:rate_type,sms|nullable|numeric',
            'selling_nonmasking_rate'=>'required_if:rate_type,sms|nullable|numeric',
            'email_rate'=>'required_if:rate_type,email|nullable|numeric'
        ]);

	$data = $request->all();
        $data['reseller_id'] = Auth::user()->reseller_id;

        Rate::create($data);

        Session::flash('message', 'Rate Added Successfully!');
        Session::flash('m-class', 'alert-success');
        if ($request->rate_type == 'sms')
            return json_encode(['url' => route('rate.list')]);
        else
            return json_encode(['url' => route('rate.list.email')]);
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
        $rate = Rate::where('reseller_id', Auth::user()->reselelr_id)->where('id', $id)->first();
        if(!$rate) {
            Session::flash('message', 'Data Not Found!');
            Session::flash('m-class', 'alert-danger');
            return json_encode(['url' => route('rate.list')]);
        }
        $title = 'Edit Rate '.$rate->name;
        return view('backend.pages.rate.edit', compact('title', 'rate'));
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
        $this->validate($request,[
            'rate_name' => ['required', Rule::unique('rates')->whereNot('id', $id)->where(function ($query) {
                return $query->where('reseller_id', Auth::user()->reseller_id);
            })],
            'rate_type'=>'required',
            'selling_masking_rate'=>'required_if:rate_type,sms|nullable|numeric',
            'selling_nonmasking_rate'=>'required_if:rate_type,sms|nullable|numeric',
            'email_rate'=>'required_if:rate_type,email|nullable|numeric'
        ]);

        $rate = Rate::findOrFail($id);
        $rate->update($request->all());
        Session::flash('message', 'Rate Updated Successfully!');
        Session::flash('m-class', 'alert-success');
        if ($request->rate_type == 'sms')
            return json_encode(['url' => route('rate.list')]);
        else
            return json_encode(['url' => route('rate.list.email')]);
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
}
