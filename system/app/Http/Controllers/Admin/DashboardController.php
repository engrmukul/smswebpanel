<?php

namespace App\Http\Controllers\Admin;

use App\Models\SiteConfig;
use App\Models\Reseller;
use App\Models\Sentmessage;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = 'Dashboard';
        $reseller = '';
        $count_reseller = '';
        $count_user = '';
        if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
            $messages = Sentmessage::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->limit(10)->get();
            $userWallet = UserWallet::where('user_id', Auth::user()->id)->first();
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $messages = Sentmessage::orderBy('created_at', 'DESC')->limit(10)->get();
                $userWallet = '';
                $count_reseller = Reseller::count();
            } else {
                $messages = Sentmessage::with(['user'])->whereHas("user", function ($query) {
                    $query->where("reseller_id", Auth::user()->reseller_id);
                })->orderBy('created_at', 'DESC')->limit(10)->get();
                $userWallet = UserWallet::where('user_id', Auth::user()->id)->first();
                $reseller = Reseller::findOrFail(Auth::user()->reseller_id);
            }
            $count_user = User::where('id_user_group', config('constants.USER_GROUP'))->where('reseller_id', Auth::user()->reseller_id)->count();
        }
        return view('backend.dashboard', compact('title', 'messages', 'userWallet', 'reseller', 'count_user', 'count_reseller'));
    }

    public function channel_status() 
    {

        // DB::table('Store_Information')
        $queryset = DB::table('sentmessages')
        ->join("user","sentmessages.user_id", "=", "user.id")
        ->select(DB::raw("username"), DB::raw("sum(case when source='API' then 1 else 0 end) API"), DB::raw("sum(case when source='WEB' then 1 else 0 end) WEB"), DB::raw("count(*) total"));

        if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
            $queryset = $queryset->where('username', Auth::user()->username);
        } 
        else 
        {
            if (!empty(Auth::user()->reseller_id)) {
                $queryset = $queryset->where('reseller_id', Auth::user()->reseller_id);
             }
        }
        $queryset->where('date', '>=', date('Y-m-d', strtotime('-90 days')));
        $channel_status = $queryset->groupBy('username')->orderBy('total', 'desc')->limit(10)->get();
        return response()->json($channel_status);
    }

    public function outbox_status() 
    {

        $queryset = DB::table('outbox')
        ->join("user","outbox.user_id", "=", "user.id")
        ->select(DB::raw("username"), DB::raw("sum(case when sms_outbox.status='Sent' then sms_outbox.smscount else 0 end) Sent"), DB::raw("sum(case when sms_outbox.status='Failed' then sms_outbox.smscount else 0 end) Failed"),
        DB::raw("sum(case when sms_outbox.status='Queue' then sms_outbox.smscount else 0 end) Queue"), DB::raw("sum(case when sms_outbox.status='Processing' then sms_outbox.smscount else 0 end) Processing"), DB::raw("sum(smscount) total"));

        if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
            $queryset = $queryset->where('username', Auth::user()->username);
        } 
        else 
        {
            if (!empty(Auth::user()->reseller_id)) {
                $queryset = $queryset->where('reseller_id', Auth::user()->reseller_id);
             }
        }
        $queryset->where('write_time', '>=', date('Y-m-d', strtotime('-90 days')));
        $channel_status = $queryset->groupBy('username')->orderBy('total', 'desc')->limit(10)->get();
        return response()->json($channel_status);
    }
}
