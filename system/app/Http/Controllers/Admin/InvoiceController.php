<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Invoice;
use App\Models\Operator;
use App\Models\Outbox;
use App\Models\Rate;
use App\Models\Reseller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    public function index(Request $request) {
        $title = 'Invoice List';
        if (empty(Auth::user()->reseller_id)) {
            $datas = Invoice::query();
        } else {
            $datas = Invoice::whereNull('reseller_id');
        }

        $ajaxUrl = route('invoice.list');

        $tableHeaders = [
            "id" => "#",
            "user" => "User",
            "invoice_from" => "Invoice From",
            "invoice_to" => "Invoice To",
            "total" => "Total Price",
            'status' => "Status"
        ];

        if (empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != config('constants.USER_GROUP')) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 1), ["reseller"=>'Reseller'], array_slice($tableHeaders, 1));
        }

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('user', function($row){
                    return $row->user->name;
                })
                ->addColumn('reseller', function($row){
                    return ($row->reseller_id)?$row->reseller->reseller_name:'-';
                })
                ->make(true);
        }
        return view('backend.pages.invoice.list', compact( 'title', 'tableHeaders', 'ajaxUrl'));
    }

    public function view($id){
        $invoice = Invoice::findOrFail($id);
        $title = 'View Invoice';
//        $number_word = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        return view('backend.pages.invoice.view', compact( 'title', 'invoice'));

    }

    public function create(Request $request)
    {
        $title = 'Create New Invoice';
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');
        $userId = '';
        $resellerId = '';
        if (isset($request->invoice_from)) {
            $startDate = $request->invoice_from;
        }
        if (isset($request->invoice_to)) {
            $endDate = $request->invoice_to;
        }

        if (isset($request->invoice_from) && isset($request->invoice_to)) {

            if (empty(Auth::user()->reseller_id)) {
                if ($request->user != '') {
                    $userId = $request->user;

                    $user = User::findOrFail($request->user);

                    $sms_rate = Rate::findOrFail($user->sms_rate_id);

                    $datas = Outbox::select(['mask', 'operator_prefix', \DB::raw('sum(smscount) as total')])->where('status', 'sent')->where('user_id', $request->user)->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $endDate])->groupBy(['mask', 'operator_prefix'])->get();

                } else {
                    $resellerId = $request->reseller;
                    $reseller = Reseller::findOrFail($request->reseller);
                    $sms_rate = Rate::findOrFail($reseller->sms_rate_id);
                    $datas = Outbox::whereHas("user", function ($query) use ($request) {
                        $query->where("reseller_id", $request->reseller);
                    })->select(['mask', 'operator_prefix', \DB::raw('sum(smscount) as total')])->where('status', 'sent')->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $endDate])->groupBy(['mask', 'operator_prefix'])->get();
                }
            } else {
                $userId = $request->user;
                $user = User::findOrFail($request->user);
                $sms_rate = Rate::findOrFail($user->sms_rate_id);
                $datas = Outbox::where('user_id', $request->user)->whereHas("user", function ($query) {
                    $query->where("reseller_id", Auth::user()->reseller_id);
                })->select(['mask', 'operator_prefix', \DB::raw('sum(smscount) as total')])->where('status', 'sent')->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $endDate])->groupBy(['mask', 'operator_prefix'])->get();
            }
        } else {
            $datas = array();
            $sms_rate = array();
        }

        $tableHeaders = [
            "id" => "#",
            "operator" => "Operator",
            "type" => "SMS Type",
            "total" => "Total SMS",
            "price" => "SMS Price",
            "total_price" => "Total Price"
        ];

        return view('backend.pages.invoice.add', compact('title', 'tableHeaders', 'datas', 'sms_rate', 'userId', 'resellerId', 'startDate', 'endDate'));
    }

    public function store(Request $request)
    {
        $invoice_details = [];
        for ($i=0; $i<$request->total_row; $i++){
            $invoice_details[] = [
                'operator' => $request->operator[$i],
                'sms_type' => $request->sms_type[$i],
                'total_sms' => $request->total_sms[$i],
                'default_price' => $request->default_price[$i],
            ];
        }
        Invoice::create([
            'user_id' => $request->user_id?:null,
            'reseller_id' => $request->reseller_id?:null,
            'invoice_from' => $request->invoice_from,
            'invoice_to' => $request->invoice_to,
            'invoice_details' => json_encode($invoice_details),
            'total' => $request->total_price,
            'status' => 'Unpaid'
        ]);

        Session::flash('message', 'Invoice Create Done!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('invoice.create')]);

    }
}
