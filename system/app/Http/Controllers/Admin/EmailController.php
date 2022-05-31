<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Domain;
use App\Models\Group;
use App\Models\Reseller;
use App\Models\SentEmail;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;



class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Requested Emails for Last 90 Days';

        $startDate = date ( 'Y-m-d', strtotime ( '-90 day' . date('Y-m-d') ) );
        $today = date('Y-m-d');

        if (Auth::user()->id_user_group == 4) {
            $messages = SentEmail::where('user_id', Auth::user()->id)->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $today]);
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $messages = SentEmail::whereBetween(DB::raw('DATE(created_at)'), [$startDate, $today]);
            } else {
                $messages = SentEmail::with(['user'])->whereHas("user", function ($query) {
                    $query->where("reseller_id", Auth::user()->reseller_id);
                })->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $today]);
            }
        }
        $messages = $messages->orderBy('created_at', 'DESC');
        $tableHeaders = ["id"=> "#", 'username'=>"User", "subject"=>"Subject", "from_email"=>"From", "date"=>"Created", "schedule_date_time"=>"Schedule Time", "status"=>"Status", 'action'=>'Action'];

        $ajaxUrl = route('email.list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($messages)
                ->addIndexColumn()
                ->addColumn('username', function($row){
                    $user = $row['user']->username;
                    return $user;
                })
                ->addColumn('from_email', function($row){
                    $from_email = $row['from_email'].' ('.$row['domain'].')';
                    return $from_email;
                })
                ->addColumn('action', function ($row){
                    if ($row['status'] == 'Draft') {
                        $csrf_field = csrf_field();
                        $btn = '<a href="' . route('email.edit', $row['id']) . '" class="warning ajax-form mr-1"><i class="fa fa-pencil"></i></a>';
                        $functionvalue = '\''.route('email.delete.post', $row["id"]).'\', \''.$row["id"].'\'';
                        $btn .= '<a href="" onclick="return deleteEmail('. $functionvalue .')" class="btn-del"><i class="fa fa-trash-o"></i></a>
                                    <form id="delete'.$row['id'].'" action="" method="POST" style="display: none;">
                                        '.$csrf_field.'
                                    </form>';

                    } else {
                        $btn = '';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.pages.email.list', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Send Email';
        $userBalance = '';
        $resellerBalance = '';
        $user_id = Auth::user()->id;
        if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
            $domains = Domain::whereRaw("find_in_set($user_id, user_id)")->get();
            $userBalance = UserWallet::where('user_id', Auth::user()->id)->select('email_balance')->first();
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $domains = Domain::all();
            } else {
                $reseller_id = Auth::user()->reseller_id;
                $domains = Domain::whereRaw("find_in_set($reseller_id, reseller_id)")->get();
                $userBalance = UserWallet::where('user_id', Auth::user()->id)->select('email_balance')->first();
                $resellerBalance = Reseller::findOrFail(Auth::user()->reseller_id);
            }
        }
        $Groups = Group::where('user_id', Auth::user()->id)->get();

        return view('backend.pages.email.add', compact('title', 'domains', 'Groups', 'resellerBalance', 'userBalance'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'message' => 'required',
            'domain' => 'required',
            'subject' => 'required',
            'from_email' => 'required',
            'group_id' => 'required_if:email_type,groupEmail',
            'recipient' => 'required_if:email_type,sendEmail',
            'schedule_time' => 'required_if:schedule,yes',
        ]);

        if ($validator->fails()) {
            print_r($request->submit);
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        if (!empty(Auth::user()->reseller_id)) {
            $reseller = Reseller::findOrFail(Auth::user()->reseller_id);
            if ($reseller->status != 'ACTIVE') {
                Session::flash('message', 'Unauthorised Access, User is inactive, Please Contact With Support!');
                Session::flash('m-class', 'alert-danger');
                return json_encode(['url' => route('email.add')]);
            }
        }

        $orderid = Auth::user()->id . time();

        $data = [
            'domain' => $request->domain,
            'message' => $request->message,
            'subject' => $request->subject,
            'recipients' => $request->recipient,
            'from_email' => $request->from_email,
            'source' => 'WEB',
            'date' => date('Y-m-d H:i:s'),
            'IP' => \Request::getClientIp(),
            'email_type' => $request->email_type,
            'user_id' => Auth::user()->id,
            'orderid' => $orderid,
            'schedule_date_time' => $request->schedule_time,
            'status' => $request->status
        ];

        if ($request->email_type == 'groupEmail') {
            $group_ids = (array)$request->group_id;
            $request->merge([
                'group' => implode(',', $group_ids)
            ]);
            $data['group_id'] = $request->group;
            $totalEmail = Contact::whereIn('group_id', $group_ids)->whereNotNull('email')->where('status', 'Active')->count();
        } else {
            $totalEmail = $request->email_count;
        }

        if ($request->status != 'Draft') {
            if ((empty(Auth::user()->reseller_id) && Auth::user()->id_user_group == 4) || !empty(Auth::user()->reseller_id)) {
                if (Auth::user()->billing_type == 'prepaid') {
                    $userWallet = UserWallet::where('user_id', Auth::user()->id)->first();
                    if ($totalEmail > $userWallet->email_balance) {
                        Session::flash('message', 'Insufficient Email Balance!');
                        Session::flash('m-class', 'alert-danger');
                        return json_encode(['url' => route('email.add')]);
                    }
                }
            }
        }

        if ($request->email_type == 'sendSms') {
            $data['recipient'] = $request->recipient;
        }

        SentEmail::create($data);
        if ($request->status == 'Draft') {
            Session::flash('message', 'Email Save As Draft Successfully!');
        } else {
            Session::flash('message', 'Email Send Successfully!');
        }
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('email.list')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SentEmail  $sentEmail
     * @return \Illuminate\Http\Response
     */
    public function show(SentEmail $sentEmail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SentEmail  $sentEmail
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $email = SentEmail::findOrFail($id);
        if($email->status != 'Draft'){
            Session::flash('message', 'This Email Not Editable!');
            Session::flash('m-class', 'alert-danger');
            return redirect()->route('email.list');
        }

        $title = 'Edit Email';
        $userBalance = '';
        $resellerBalance = '';
        $user_id = Auth::user()->id;
        if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
            $domains = Domain::whereRaw("find_in_set($user_id, user_id)")->get();
            $userBalance = UserWallet::where('user_id', Auth::user()->id)->select('email_balance')->first();
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $domains = Domain::all();
            } else {
                $reseller_id = Auth::user()->reseller_id;
                $domains = Domain::whereRaw("find_in_set($reseller_id, reseller_id)")->get();
                $userBalance = UserWallet::where('user_id', Auth::user()->id)->select('email_balance')->first();
                $resellerBalance = Reseller::findOrFail(Auth::user()->reseller_id);
            }
        }
        $Groups = Group::where('user_id', Auth::user()->id)->get();

        return view('backend.pages.email.edit', compact('title', 'email', 'domains', 'Groups', 'resellerBalance', 'userBalance'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $email = SentEmail::find($id);
        $validator = \Validator::make($request->all(), [
            'message' => 'required',
            'domain' => 'required',
            'subject' => 'required',
            'from_email' => 'required',
            'group_id' => 'required_if:email_type,groupEmail',
            'recipient' => 'required_if:email_type,sendEmail',
            'schedule_time' => 'required_if:schedule,yes',
        ]);

        if ($validator->fails()) {
            print_r($request->submit);
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        if (!empty(Auth::user()->reseller_id)) {
            $reseller = Reseller::findOrFail(Auth::user()->reseller_id);
            if ($reseller->status != 'ACTIVE') {
                Session::flash('message', 'Unauthorised Access, User is inactive, Please Contact With Support!');
                Session::flash('m-class', 'alert-danger');
                return json_encode(['url' => route('email.add')]);
            }
        }

        $data = [
            'domain' => $request->domain,
            'message' => $request->message,
            'subject' => $request->subject,
            'recipients' => $request->recipient,
            'from_email' => $request->from_email,
            'source' => 'WEB',
            'date' => date('Y-m-d H:i:s'),
            'IP' => \Request::getClientIp(),
            'email_type' => $request->email_type,
            'user_id' => Auth::user()->id,
            'schedule_date_time' => $request->schedule_time,
            'status' => $request->status
        ];

        if ($request->email_type == 'groupEmail') {
            $group_ids = (array)$request->group_id;
            $request->merge([
                'group' => implode(',', $group_ids)
            ]);

            $totalEmail = Contact::whereIn('group_id', $group_ids)->whereNotNull('email')->where('status', 'Active')->count();
        } else {
            $totalEmail = $request->email_count;
        }
        $data['group_id'] = $request->group;
//        dd($data);

        if ($request->status != 'Draft') {
            if ((empty(Auth::user()->reseller_id) && Auth::user()->id_user_group == 4) || !empty(Auth::user()->reseller_id)) {
                if (Auth::user()->billing_type == 'prepaid') {
                    $userWallet = UserWallet::where('user_id', Auth::user()->id)->first();
                    if ($totalEmail > $userWallet->email_balance) {
                        Session::flash('message', 'Insufficient Email Balance!');
                        Session::flash('m-class', 'alert-danger');
                        return json_encode(['url' => route('email.add')]);
                    }
                }
            }
        }

//        if ($request->email_type == 'sendSms') {
//            $data['recipient'] = $request->recipient;
//        }

        $email->update($data);
        if ($request->status == 'Draft') {
            Session::flash('message', 'Email Save As Draft Successfully!');
        } else {
            Session::flash('message', 'Email Send Successfully!');
        }
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('email.list')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SentEmail  $sentEmail
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $email = SentEmail::find($id);
        $email->delete();
        Session::flash('message', 'Email Deleted Successfully!');
        Session::flash('m-class', 'alert-success');
        return redirect()->back();
    }
}
