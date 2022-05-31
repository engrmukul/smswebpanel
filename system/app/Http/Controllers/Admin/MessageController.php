<?php

namespace App\Http\Controllers\Admin;

use App\Classes\SmsCount;
use App\Http\Controllers\Controller;
use App\Imports\FileImport;
use App\Imports\MessageImport;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Rate;
use App\Models\Reseller;
use App\Models\SenderId;
use App\Models\SentEmail;
use App\Models\Sentmessage;
use App\Models\Template;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Requested Message Last 90 Days';

        $startDate = date('Y-m-d', strtotime('-90 day' . date('Y-m-d')));
        $today = date('Y-m-d');



        $tableHeaders = ["id" => "#", 'username' => "User", "source" => "Source", "senderID" => "Mask", "recipient"=> "Recipient", "message" => "Message",
            "date" => "Request Time", "scheduleDateTime" => "Schedule Time", "status" => "Status"];

        $ajaxUrl = route('message.list');

        if ($this->ajaxDatatable()) {
            
            if (Auth::user()->id_user_group == 4) {
                $messages = Sentmessage::where('user_id', Auth::user()->id)->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $today]);
            } else {
                if (empty(Auth::user()->reseller_id)) {
                    $messages = Sentmessage::whereBetween(DB::raw('DATE(created_at)'), [$startDate, $today])->orderBy('created_at', 'DESC');
                } else {
                    $messages = Sentmessage::with(['user'])->whereHas("user", function ($query) {
                        $query->where("reseller_id", Auth::user()->reseller_id);
                    })->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $today])->orderBy('created_at', 'DESC');
                }
            }
            $messages = $messages->orderBy('created_at', 'DESC');

            return DataTables::of($messages)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    $user = $row['user']->username;
                    return $user;
                })
                ->addColumn('senderID', function ($row) {
                    ($row['senderID']) ? $senderId = $row['senderID'] : $senderId = '-';
                    return $senderId;
                })
                ->rawColumns(['username', 'senderID'])
                ->make(true);
        }

        return view('backend.pages.message.list', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendNonMaskingSms()
    {
        $title = 'Send New Message';
        $userBalance = '';
        $resellerBalance = '';
        $user_id = Auth::user()->id;
        if (Auth::user()->id_user_group == 4) {
            $senderIds = SenderId::whereRaw("find_in_set($user_id, user_id)")->get();
            $userBalance = UserWallet::where('user_id', Auth::user()->id)->select('non_masking_balance')->first();
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $senderIds = SenderId::all();
            }
            if (!empty(Auth::user()->reseller_id)) {
                $userBalance = UserWallet::where('user_id', Auth::user()->id)->select('non_masking_balance')->first();
                $resellerBalance = Reseller::findOrFail(Auth::user()->reseller_id);
            }
        }

        Session::put('success_url', 'message.add');

        $phoneGroups = Group::where('user_id', Auth::user()->id)->get();

        $templates = Template::where('user_id', Auth::user()->id)->get();

        return view('backend.pages.message.add', compact('title','senderIds', 'phoneGroups', 'templates', 'resellerBalance', 'userBalance'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendMaskingSms()
    {
        $title = 'Send New Message';
        $userBalance = '';
        $resellerBalance = '';
        $user_id = Auth::user()->id;
        if (Auth::user()->id_user_group == 4) {
            $senderIds = SenderId::whereRaw("find_in_set($user_id, user_id)")->get();
            $userBalance = UserWallet::where('user_id', Auth::user()->id)->select('masking_balance')->first();
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $senderIds = SenderId::all();
            } else {
                $reseller_id = Auth::user()->reseller_id;
                $senderIds = SenderId::whereRaw("find_in_set($reseller_id, reseller_id)")->get();
                $userBalance = UserWallet::where('user_id', Auth::user()->id)->select('masking_balance')->first();
                $resellerBalance = Reseller::findOrFail(Auth::user()->reseller_id);
            }
        }

        Session::put('success_url', 'message.add.masking');
        $phoneGroups = Group::where('user_id', Auth::user()->id)->get();

        $templates = Template::where('user_id', Auth::user()->id)->get();

        return view('backend.pages.message.sendmaskingsms', compact('title', 'senderIds', 'phoneGroups', 'templates', 'resellerBalance', 'userBalance'));
    }

    public function sendDynamicSms()
    {
        $title = 'Send Dynamic Message';
        $userBalance = '';
        $resellerBalance = '';
        $user_id = Auth::user()->id;
        if (Auth::user()->id_user_group == 4) {
            $senderIds = SenderId::whereRaw("find_in_set($user_id, user_id)")->get();
            $userBalance = UserWallet::where('user_id', Auth::user()->id)->first();
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $senderIds = SenderId::all();
            } else {
                $reseller_id = Auth::user()->reseller_id;
                $senderIds = SenderId::whereRaw("find_in_set($reseller_id, reseller_id)")->get();
                $userBalance = UserWallet::where('user_id', Auth::user()->id)->first();
                $resellerBalance = Reseller::findOrFail(Auth::user()->reseller_id);
            }
        }

        $templates = Template::where('user_id', Auth::user()->id)->get();

        return view('backend.pages.message.senddynamicsms', compact('title', 'senderIds', 'templates', 'resellerBalance', 'userBalance'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'message' => 'required',
            'mask' => 'required_if:is_mask,mask',
            'group_id' => 'required_if:sms_type,groupSms',
            'number' => 'required_if:sms_type,sendSms',
            'file' => 'required_if:sms_type,fileSms',
            'schedule_time' => 'required_if:schedule,yes',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        if (!empty(Auth::user()->reseller_id)) {
            $reseller = Reseller::findOrFail(Auth::user()->reseller_id);
            if ($reseller->status != 'ACTIVE') {
                Session::flash('message', 'Unauthorised Access, User inactive, Please contact with support!');
                Session::flash('m-class', 'alert-danger');
                return json_encode(['url' => route(Session::get('success_url'))]);
            }
        }

//        //Only unicode Sms Accept
//        if ($request->isunicode != 'unicode') {
//            Session::flash('message', 'Only Unicode SMS Accepted!');
//            Session::flash('m-class', 'alert-danger');
//            return redirect()->back();
//        }

        $orderid = Auth::user()->id . time();

        $data = [
            'senderID' => $request->mask,
            'message' => $request->message,
            'source' => 'WEB',
            'date' => date('Y-m-d H:i:s'),
            'IP' => \Request::getClientIp(),
            'sms_type' => $request->sms_type,
            'user_id' => Auth::user()->id,
            'orderid' => $orderid,
            'scheduleDateTime' => $request->schedule_time,
            'status' => $request->status
        ];

        if ($request->isunicode == 'unicode') {
            $data['is_unicode'] = 1;
        }
        if ($request->isunicode == 'gsmextended') {
            $data['is_unicode'] = 2;
        }

        if ($request->sms_type == 'groupSms') {
            $group_ids = (array)$request->group_id;
            $request->merge([
                'group' => implode(',', $group_ids)
            ]);
            $data['group_id'] = $request->group;
            $contactCount = Contact::whereIn('group_id', $group_ids)->where('status', 'Active')->count();
        } else {
            $contactCount = $request->number_count;
        }
        $smsCount = $request->totalsmscount;

        $totalSmsCount = $contactCount * $smsCount;
        $data['sms_count'] = $totalSmsCount;

        if ($request->status != 'Draft') {
            if ((empty(Auth::user()->reseller_id) && Auth::user()->id_user_group == 4) || !empty(Auth::user()->reseller_id)) {
                if (Auth::user()->billing_type == 'prepaid') {
                    $userWallet = UserWallet::where('user_id', Auth::user()->id)->first();
                    if ($request->mask != '' && $totalSmsCount > $userWallet->masking_balance) {
                        Session::flash('message', 'Insufficient Masking Balance!');
                        Session::flash('m-class', 'alert-danger');
                        return json_encode(['url' => route(Session::get('success_url'))]);
                    }

                    if ($request->mask == '' && $totalSmsCount > $userWallet->non_masking_balance) {
                        Session::flash('message', 'Insufficient Non Masking Balance!');
                        Session::flash('m-class', 'alert-danger');
                        return json_encode(['url' => route(Session::get('success_url'))]);
                    }
                }
            }
        }

        if ($request->sms_type == 'fileSms') {
            $request->validate([
                'file' => 'required|mimes:csv,xlx,xlsx|max:25600' //25 MB
            ], ['file.max' => 'The file may not be greater than 25 megabytes']);
            if($request->file()) {
                $fileName = $orderid.Str::random(10).'.'.$request->file->getClientOriginalExtension();
                $request->file->move('assets/uploads/messages/',$fileName);
            }

            /*$file = $request->file;
            $excels = Excel::toArray([], $file);
            $length = count($excels);

            for ($k = 0; $k < $length; $k++) {
                $filelength = count($excels[$k]);
                for ($i = 0; $i < $filelength; $i++) {
                    $new_file_data[] = $excels[$k][$i];
                }
            }


            if (strtolower($new_file_data[0][0]) != 'number') {
                return response()->json(array(
                    'errors' => ['file' => ['Add Header name "Number" in your file!']]
                ), 400);
            }

            $fileName = md5($file->getClientOriginalName() . time()) . ".csv";
            $file_path = 'assets/uploads/messages/' . $fileName;
            $fp = fopen($file_path, 'w');
            foreach ($new_file_data as $fields) {
                fputcsv($fp, $fields);
            }
            fclose($fp);*/

            $data['file'] = $fileName;

        }

        if ($request->sms_type == 'sendSms') {
            $data['recipient'] = $request->number;
        }

        Sentmessage::create($data);
        Session::flash('message', 'Message Send Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('message.list')]);
    }


    public function storeDynamic(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'message' => 'required',
            'file' => 'required',
            'mobile_no_column' => 'required',
            'schedule_time' => 'required_if:schedule,yes',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }
        if (!empty(Auth::user()->reseller_id)) {
            $reseller = Reseller::findOrFail(Auth::user()->reseller_id);
            if ($reseller->status != 'ACTIVE') {
                Session::flash('message', 'Unauthorised Access, User inactive, Please contact with support!');
                Session::flash('m-class', 'alert-danger');
                return json_encode(['url' => route('message.add.dynamic')]);
            }
        }

        $orderid = Auth::user()->id . time();

        $data = [
            'senderID' => $request->mask,
            'message' => $request->message,
            'mobile_no_column' => $request->mobile_no_column,
            'source' => 'WEB',
            'date' => date('Y-m-d H:i:s'),
            'IP' => \Request::getClientIp(),
            'sms_type' => 'DynamicSms',
            'user_id' => Auth::user()->id,
            'orderid' => $orderid,
            'scheduleDateTime' => $request->schedule_time,
            'status' => $request->status
        ];


        $file = $request->file;
        $excels = Excel::toArray([], $file);

        $length = count($excels);

        for ($k = 0; $k < $length; $k++) {
            $filelength = count($excels[$k]);
            for ($i = 0; $i < $filelength; $i++) {
                $nLength = count($excels[$k][$i]);
                $rowData[] = $excels[$k][$i];
                for ($t = 0; $t < $nLength; $t++) {
                    $finalData[$excels[$k][0][$t]][] = $excels[$k][$i][$t];
                }
            }
        }

        $smsCount = new SmsCount();

        $totalNumber = count($rowData) - 1;

        $totalSmsCount = 0;

        $sms_type = '';

        for ($r = 1; $r <= $totalNumber; $r++) {
            $message = $request->message;
            foreach ($rowData[0] as $value) {
                $replaceValue = $finalData[$value][$r];
                $message = str_replace("{" . $value . "}", $replaceValue, $message);
            }
            $smsInfo = $smsCount->countSms($message);
            $totalSmsCount += $smsInfo->count;
//            //Only unicode Sms Accept
//            if ($smsInfo->smsType == 'unicode') {
//                $sms_type = 'unicode';
//            } else {
//                Session::flash('message', 'Only Unicode SMS Accepted!');
//                Session::flash('m-class', 'alert-danger');
//                return redirect()->back();
//            }

            if ($sms_type == 'unicode') {
                continue;
            } else if ($sms_type == 'gsm_extend') {
                if ($smsInfo->smsType == 'unicode') {
                    $sms_type = 'unicode';
                } else {
                    continue;
                }
            } else {
                $sms_type = $smsInfo->smsType;
            }
        }

        $data['sms_count'] = $totalSmsCount;

        if ($sms_type == 'unicode') {
            $data['is_unicode'] = 1;
        }
        if ($sms_type == 'gsmextended') {
            $data['is_unicode'] = 2;
        }

        if ($request->status != 'Draft') {
            if ((empty(Auth::user()->reseller_id) && Auth::user()->id_user_group == 4) || !empty(Auth::user()->reseller_id)) {
                if (Auth::user()->billing_type == 'prepaid') {
                    $userWallet = UserWallet::where('user_id', Auth::user()->id)->first();
                    if ($request->mask != '' && $totalSmsCount > $userWallet->masking_balance) {
                        Session::flash('message', 'Insufficient Masking Balance!');
                        Session::flash('m-class', 'alert-danger');
                        return json_encode(['url' => route('message.add.dynamic')]);
                    }

                    if ($request->mask == '' && $totalSmsCount > $userWallet->non_masking_balance) {
                        Session::flash('message', 'Insufficient Non Masking Balance!');
                        Session::flash('m-class', 'alert-danger');
                        return json_encode(['url' => route('message.add.dynamic')]);
                    }
                }
            }
        }

        $fileName = md5($file->getClientOriginalName() . time()) . ".csv";
        $file_path = 'assets/uploads/messages/' . $fileName;
        $fp = fopen($file_path, 'w');
        foreach ($rowData as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        $data['file'] = $fileName;

        Sentmessage::create($data);
        Session::flash('message', 'Message Send Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('message.list')]);
    }

    public function campaignStore(Request $request)
    {
        $validate = [
            'message' => 'required_if:campaign_type,sms',
            'email' => 'required_if:campaign_type,email',
            'subject' => 'required_if:campaign_type,email',
            'domain' => 'required_if:campaign_type,email',
            'campaign_type' => 'required',
            'schedule_time' => 'required_if:schedule,yes',
        ];

        if (empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4 && empty($request->user)) {
            $validate['user'] = 'required';
        }

        $validator = \Validator::make($request->all(), $validate);

        if ($request->campaign_type == 'email') {
            if (!empty($request->from_email)) {
                $domain = explode('@', $request->from_email)[1];
            } else {
                $domain = '';
            }
            if ($domain != $request->domain) {
                $validator->after(function ($validator) {
                    $validator->errors()->add(
                        'from_email', 'Email dose not match with domain!'
                    );
                });
            };
        }

        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }


        if (!empty(Auth::user()->reseller_id)) {
            $reseller = Reseller::findOrFail(Auth::user()->reseller_id);
            if ($reseller->status != 'ACTIVE') {
                Session::flash('message', 'Unauthorised Access, User inactive, Please contact with support!');
                Session::flash('m-class', 'alert-danger');
                return json_encode(['url' => route('phonebook.contact.list')]);
            }
        }


//        //Only unicode Sms Accept
//        if ($request->isunicode != 'unicode') {
//            Session::flash('message', 'Only Unicode SMS Accepted!');
//            Session::flash('m-class', 'alert-danger');
//            return redirect()->back();
//        }

        if (empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4) {
            $user = User::findOrFail($request->user);
        } else {
            $user = User::findOrFail(Auth::user()->id);
        }

        $orderid = $user->id . time();

        if ($request->campaign_type == 'sms') {

            $data = [
                'senderID' => $request->mask,
                'message' => $request->message,
                'source' => 'WEB',
                'date' => date('Y-m-d H:i:s'),
                'IP' => \Request::getClientIp(),
                'sms_type' => 'campaignSms',
                'user_id' => $user->id,
                'orderid' => $orderid,
                'search_param' => $request->search_param,
                'scheduleDateTime' => $request->schedule_time
            ];

            if ($request->isunicode == 'unicode') {
                $data['is_unicode'] = 1;
            }
            if ($request->isunicode == 'gsmextended') {
                $data['is_unicode'] = 2;
            }

            $contactCount = $request->totalNumber;
            $smsCount = $request->totalsmscount;

            $totalSmsCount = $contactCount * $smsCount;
            $data['sms_count'] = $totalSmsCount;

            if ((empty($user->reseller_id) && $user->id_user_group == 4) || !empty($user->reseller_id)) {
                if ($user->billing_type == 'prepaid') {
                    $userWallet = UserWallet::where('user_id', $user->id)->first();
                    if ($request->mask != '' && $totalSmsCount > $userWallet->masking_balance) {
                        Session::flash('message', 'Insufficient Masking Balance!');
                        Session::flash('m-class', 'alert-danger');
                        return json_encode(['url' => route('phonebook.contact.list')]);
                    }

                    if ($request->mask == '' && $totalSmsCount > $userWallet->non_masking_balance) {
                        Session::flash('message', 'Insufficient Non Masking Balance!');
                        Session::flash('m-class', 'alert-danger');
                        return json_encode(['url' => route('phonebook.contact.list')]);
                    }
                }
            }
            Sentmessage::create($data);
        } else {
            $data = [
                'domain' => $request->domain,
                'message' => $request->email,
                'from_email' => $request->from_email,
                'subject' => $request->subject,
                'source' => 'WEB',
                'date' => date('Y-m-d H:i:s'),
                'IP' => \Request::getClientIp(),
                'email_type' => 'campaignEmail',
                'user_id' => $user->id,
                'orderid' => $orderid,
                'search_param' => $request->search_param,
                'schedule_date_time' => $request->schedule_time
            ];

            $totalEmail = $request->totalNumber;

            if ((empty($user->reseller_id) && $user->id_user_group == 4) || !empty($user->reseller_id)) {
                if ($user->billing_type == 'prepaid') {
                    $userWallet = UserWallet::where('user_id', $user->id)->first();
                    if ($totalEmail > $userWallet->email_balance) {
                        Session::flash('message', 'Insufficient Email Balance!');
                        Session::flash('m-class', 'alert-danger');
                        return json_encode(['url' => route('phonebook.contact.list')]);
                    }
                }
            }
            SentEmail::create($data);
        }

        Session::flash('message', 'Campaign Start Successfully!');
        Session::flash('m-class', 'alert-success');
        if ($request->campaign_type == 'email'){
            return json_encode(['url' => route('email.list')]);
        } else {
            return json_encode(['url' => route('message.list')]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function uploadImage(Request $request)
    {
        $path_url = 'assets/images/uploads';
        if ($request->hasFile('file')) {
            $originName = $request->file('file')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $fileName = \Str::slug($fileName) . '_' . time() . '.' . $extension;
            $request->file('file')->move($path_url, $fileName);
            $url = asset($path_url . '/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
    }
}
