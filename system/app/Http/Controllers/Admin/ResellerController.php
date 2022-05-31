<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteConfig;
use App\Models\Rate;
use App\Models\Reseller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class ResellerController extends Controller
{
    public function index()
    {
        $title = 'Reseller List';
        $resellers = Reseller::query();

        $tableHeaders = [
            'id' => "#",
            'reseller_name' => 'Name',
            'address' => 'Address',
            'sms_rate' => 'SMS Rate',
            'email_rate' => 'Email Rate',
            'available_balance' => 'Available Balance',
            'due' => 'Due',
            'phone' => 'Phone',
            'tps' => 'TPS',
            'status' => 'Status',
            'action' => 'Manage'
        ];

        if ($this->ajaxDatatable()) {
            return DataTables::of($resellers)
                ->addColumn('status', function ($row) {
                    $st = ($row['status'] == 'Active') ? 'primary' : (($row['status'] == 'Inactive') ? 'danger' : 'warning');
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('sms_rate', function ($row){
                    return $row['smsRate']?$row['smsRate']->rate_name:'';
                })
                ->addColumn('email_rate', function ($row){
                    return $row['emailRate']?$row['emailRate']->rate_name:'';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('reseller.edit', $row['id']) . '" class="warning mr-1"><i class="fa fa-pencil"></i></a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $ajaxUrl = route('reseller.list');

        return view('backend.pages.reseller.list', compact('title', 'ajaxUrl', 'tableHeaders'));
    }

    public function add()
    {
        $title = 'Reseller Add';
        $smsRates = Rate::where('rate_type', 'sms')->get();
        $emailRates = Rate::where('rate_type', 'email')->get();
        return view('backend.pages.reseller.add', compact('title', 'smsRates', 'emailRates'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'reseller_name' => 'required|max:50|unique:resellers',
            'phone' => 'required|numeric',
            'email' => 'required',
            'address' => 'required',
            'thana' => 'required',
            'district' => 'required',
            'sms_rate' => 'required',
            'email_rate' => 'required',
            'tps' => 'nullable|numeric',
            'site_url' => 'required|unique:site_configs',
        ]);

        $data = $request->all();
        if ($request->tps == '') {
            $data['tps'] = 0;
        }
        $data['sms_rate_id'] = $request->sms_rate;
        $data['email_rate_id'] = $request->email_rate;
        $data['status'] = 'PENDING';
        $reseller_id = Reseller::create($data)->id;
        $data['brand_name'] = $request->reseller_name;
        $data['reseller_id'] = $reseller_id;
        SiteConfig::create($data);
        Session::flash('message', 'Reseller Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('reseller.list')]);
    }

    public function edit($id)
    {
        $edit = Reseller::findOrFail($id);
        $title = 'Edit Reseller';
        $smsRates = Rate::where('rate_type', 'sms')->get();
        $emailRates = Rate::where('rate_type', 'email')->get();
        $configdata = Config::select('site_url')->where('reseller_id', $id)->first();
        return view('backend.pages.reseller.edit', compact('edit', 'title', 'configdata', 'smsRates', 'emailRates'));
    }

    public function update(Request $request, $id)
    {
        $reseller = Reseller::findOrFail($id);
        $config = SiteConfig::where('reseller_id', $id)->first();
        $this->validate($request, [
            'reseller_name' => 'required|max:50|unique:resellers,reseller_name,' . $reseller->id . '',
            'phone' => 'required|numeric',
            'email' => 'required',
            'address' => 'required',
            'thana' => 'required',
            'district' => 'required',
            'sms_rate' => 'required',
            'email_rate' => 'required',
            'tps' => 'nullable|numeric',
            'site_url' => 'required|max:50|unique:site_configs,site_url,' . $config->id . ''
        ]);

        $data = $request->all();
        if ($request->tps == '') {
            $data['tps'] = 0;
        }
        $data['sms_rate_id'] = $request->sms_rate;
        $data['email_rate_id'] = $request->email_rate;
        $reseller->update($data);
        $data['brand_name'] = $request->reseller_name;
        $config = SiteConfig::where('reseller_id', $id)->first();
        $config->update($data);
        Session::flash('message', 'Reseller Updated Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('reseller.list')]);
    }

    public function changeStatus(Request $request, $id)
    {
        $user = Reseller::findOrFail($id);
        $data['status'] = $request->status;
        $user->update($data);
        echo Controller::status_change($id, $request->status);
    }
}
