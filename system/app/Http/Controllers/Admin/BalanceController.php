<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuyBundle;
use App\Models\DepositHistory;
use App\Models\Rate;
use App\Models\Reseller;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Null_;
use Yajra\DataTables\DataTables;

class BalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function resellerTransferList()
    {
        $title = 'Reseller Transfer List';
        if (Auth::user()->reseller_id == null){
            $histories = DepositHistory::whereNotNull("reseller_id")->orderBy('created_at', 'DESC');
        } else {
            $histories = DepositHistory::where("reseller_id", Auth::user()->reseller_id)->orderBy('created_at', 'DESC');
        }

        $tableHeaders = [
            'id' => "#",
            'reseller' => 'Reseller Name',
            'deposit_amount' => 'Deposit Amount',
            'created_at' => 'Deposit Date',
            'approved_date' => 'Approved Date',
            'deposit_by' => 'Deposit By',
            'status' => 'Status'
        ];

        if ($this->ajaxDatatable()) {
            return DataTables::of($histories)
                ->addColumn('status', function ($row) {
                    $st = ($row['status'] == 'Approved') ? 'primary' : 'danger';
                    $status = ($row['status'] == 'Approved') ? '' : 'status';
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="'.$status.'" data-type="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('approved_date', function ($row) {
                    return '<span class="approved_date_' . $row['id'] . '">' . $row['approved_date'] . '</span>';
                })
                ->addColumn('created_at', function ($row) {
                    return '<span>' . $row['created_at'] . '</span>';
                })
                ->addColumn('deposit_by', function ($row){
                    return $row['depositBy']->name;
                })
                ->addColumn('reseller', function ($row){
                    return ($row['reseller'])?$row['reseller']->reseller_name: '-';
                })
                ->addIndexColumn()
                ->rawColumns(['status', 'created_at', 'approved_date'])
                ->make(true);
        }

        $ajaxUrl = route('balance.transfer.list.reseller');

        $approvedUrl = 'balance.status.reseller';
        if(empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4){
            $approvedPermission = 'Yes';
        } else {
            $approvedPermission = 'No';
        }

        return view('backend.pages.balance.balanceTransferList', compact('title', 'ajaxUrl', 'tableHeaders', 'approvedUrl', 'approvedPermission'));
    }

    public function userTransferList()
    {
        $title = 'User Transfer List';
        if (Auth::user()->id_user_group == 4){
            $histories = DepositHistory::with(['user'])->where("user_id", Auth::user()->id)->orderBy('created_at', 'DESC');
        } else {
            $histories = DepositHistory::with(['user'])->whereHas("user", function ($query) {
                $query->where("reseller_id", Auth::user()->reseller_id);
            })->orderBy('created_at', 'DESC');
        }

        $tableHeaders = [
            'id' => "#",
            'user' => 'User Name',
            'deposit_amount' => 'Deposit Amount',
            'created_at' => 'Deposit Date',
            'approved_date' => 'Approved Date',
            'deposit_by' => 'Deposit By',
            'status' => 'Status'
        ];

        if ($this->ajaxDatatable()) {
            return DataTables::of($histories)
                ->addColumn('status', function ($row) {
                    $st = ($row['status'] == 'Approved') ? 'primary' : 'danger';
                    $status = ($row['status'] == 'Approved') ? '' : 'status';
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="'.$status.'" data-type="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('approved_date', function ($row) {
                    return '<span class="approved_date_' . $row['id'] . '">' . $row['approved_date'] . '</span>';
                })
                ->addColumn('created_at', function ($row) {
                    return '<span>' . $row['created_at'] . '</span>';
                })
                ->addColumn('deposit_by', function ($row){
                    return $row['depositBy']->name;
                })
                ->addColumn('user', function ($row){
                    return ($row['user'])?$row['user']->name: '-';
                })
                ->addIndexColumn()
                ->rawColumns(['status', 'created_at', 'approved_date'])
                ->make(true);
        }

        $ajaxUrl = route('balance.transfer.list.user');

        $approvedUrl = 'balance.status.user';
        if(Auth::user()->id_user_group != 4){
            $approvedPermission = 'Yes';
        } else {
            $approvedPermission = 'No';
        }

        return view('backend.pages.balance.balanceTransferList', compact('title', 'ajaxUrl', 'tableHeaders', 'approvedUrl', 'approvedPermission'));
    }

    public function userWallet()
    {
        $title = 'User Wallet';

        if (empty(Auth::user()->reseller_id)) {
            $datas = UserWallet::with(['user'])->whereHas("user", function ($query) {
                $query->where('status', 'Active');
            })->orderBy('created_at', 'DESC');
        } else {
            $datas = UserWallet::with(['user'])->whereHas("user", function ($query) {
                $query->where("reseller_id", Auth::user()->reseller_id)->where('status', 'Active');
            })->orderBy('created_at', 'DESC');
        }
        $tableHeaders = ["id"=> "#", "username"=>"Name", 'available_balance'=>"Available Balance", 'masking_balance'=>'Masking Balance', 'non_masking_balance'=>'Non Masking Balance'];

        if (empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 5), ["reseller"=>'Reseller'], array_slice($tableHeaders, 5));
        }

       if(config('constants.EMAIL_SERVICE')==true) {
           $tableHeaders = array_merge($tableHeaders, array('email_balance'=> 'Email Balance'));
       }


        $ajaxUrl = route('balance.wallet.user.list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('username', function($row){
                    return $row->user->name . ' ('. $row->user->username .')';
                })
                ->addColumn('reseller', function($row){
                    return ($row->reseller_id)?$row->user->reseller->reseller_name:'-';
                })
                ->make(true);
        }

        return view('backend.pages.balance.userWallet', compact('title', 'tableHeaders', 'ajaxUrl'));
    }


    public function resellerWallet()
    {
        $title = 'Reseller Wallet';
        $datas = Reseller::orderBy('created_at', 'DESC');
        $tableHeaders = ["id"=> "#", "reseller_name"=>"Name", 'available_balance'=>"Available Balance"];

        $ajaxUrl = route('balance.wallet.reseller.list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.pages.balance.resellerWallet', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addUserBalance()
    {
        $title = 'User Balance Add';
        $user_id = Auth::user()->id;
        if (empty(Auth::user()->reseller_id)) {
            $users = User::where('reseller_id', Auth::user()->reseller_id)->where('id_user_group', 4)->whereRaw("find_in_set($user_id, assign_user_id)")->get();
        } else {
            if (Auth::user()->id_user_group == 3) {
                $users = User::where('reseller_id', Auth::user()->reseller_id)->get();
            } else {
                $users = User::where('reseller_id', Auth::user()->reseller_id)->whereRaw("find_in_set($user_id, assign_user_id)")->get();
            }
        }

        return view('backend.pages.balance.adduserbalance', compact('title', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addResellerBalance()
    {
        $title = 'Reseller Balance Add';
        $resellers = Reseller::get();
        return view('backend.pages.balance.addresellerbalance', compact('title', 'resellers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|gt:0'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        if (!empty(Auth::user()->reseller_id)) {
            $reseller = Reseller::findOrFail(Auth::user()->reseller_id);
            if ($request->amount > $reseller->available_balance) {
                return response()->json(array(
                    'errors' => ['amount' => ['Insufficient Balance!']]
                ), 400);
            }
        }

        $data = [
            'user_id' => $request->user,
            'reseller_id' => $request->reseller,
            'deposit_amount' => $request->amount,
            'masking_balance' => $request->masking_balance,
            'non_masking_balance' => $request->non_masking_balance,
            'comment' => $request->comment,
            'deposit_by' => Auth::user()->id
        ];

        DepositHistory::create($data);
        Session::flash('message', 'User Added Successfully!');
        Session::flash('m-class', 'alert-success');
        if ($request->user != null) {
            return json_encode(['url' => route('balance.transfer.list.user')]);
        } else {
            return json_encode(['url' => route('balance.transfer.list.reseller')]);
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

    public function changeStatusUser(Request $request, $id)
    {
        $depositHistory = DepositHistory::findOrFail($id);
        $userWallet = UserWallet::where('user_id', $depositHistory->user_id)->first();
        $userWalletData['available_balance'] = $userWallet->available_balance + $depositHistory->deposit_amount;

        if (!empty(Auth::user()->reseller_id)) {
            $reseller = Reseller::findOrFail(Auth::user()->reseller_id);
//            $rate = Rate::findOrFail($reseller->rate_id);
//            $total_price = ($depositHistory->masking_balance * $rate->selling_masking_rate) + ($depositHistory->non_masking_balance * $rate->selling_nonmasking_rate);

            if($depositHistory->deposit_amount > $reseller->available_balance) {
                $res = Controller::status_change($id, 'Pending');
                $result = (array)json_decode($res);
                $result["date"] = '';
                return json_encode($result);
            }

            $resellerData = [
                'available_balance' => $reseller->available_balance - $depositHistory->deposit_amount
            ];

            $reseller->update($resellerData);
        }

        $userWallet->update($userWalletData);

        $data['status'] = $request->status;
        $data['approved_date'] = date('Y-m-d H:i:s');
        $depositHistory->update($data);
        $res = Controller::status_change($id, $request->status);
        $result = (array)json_decode($res);
        $result["date"] = date('Y-m-d H:i:s');
        echo json_encode($result);
    }

    public function changeStatusReseller(Request $request, $id)
    {
        $depositeHistoty = DepositHistory::findOrFail($id);
        $reseller = Reseller::findOrFail($depositeHistoty->reseller_id);
        $resellerWalletData['available_balance'] = $reseller->available_balance + $depositeHistoty->deposit_amount;
        $reseller->update($resellerWalletData);
        $data['status'] = $request->status;
        $data['approved_date'] = date('Y-m-d H:i:s');
        $depositeHistoty->update($data);
        $res = Controller::status_change($id, $request->status);
        $result = (array)json_decode($res);
        $result["date"] = date('Y-m-d H:i:s');
        echo json_encode($result);
    }

    public function buyBundle(){
        $title = "Buy Bundle";
        if(Auth::user()->id_user_group == 4  or Auth::user()->reseller_id != null){
            $userWallet = UserWallet::where('user_id', Auth::user()->id)->first();
            return view('backend.pages.balance.buyBundle', compact('title', 'userWallet'));
        } else {
            Session::flash('message', 'You don\'t need to do this!');
            Session::flash('m-class', 'alert-danger');
            return redirect()->back();
        }

    }

    public function buyBundlePost(Request $request){
//        dd($request);
//        if ($request->masking_sms == 0 && $request->non_masking_sms == 0 && $request->email == 0) {
//            return response()->json(array(
//                'errors' => [
//                    'masking_sms' => ['You can not add all balance 0.'],
//                    'non_masking_sms' => ['You can not add all balance 0.'],
//                    'email' => ['You can not add all balance 0.'],
//                ]
//            ), 400);
//        }

        $validator = Validator::make($request->all(), [
            'masking_sms' => 'required|integer|min:0',
            'non_masking_sms' => 'required|integer|min:0',
            'email' => 'filled|integer|min:0'
        ]);


        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $userWallet = UserWallet::where('user_id', Auth::user()->id)->first();
        $sms_rate = null;
        $email_rate = null;

        if (Auth::user()->id_user_group == 4) {
            $sms_rate = Rate::where('id', Auth::user()->sms_rate_id)->first();
            if (Auth::user()->email_rate_id) {
                $email_rate = Rate::where('id', Auth::user()->email_rate_id)->first();
            }
        } else {
            $reseller = Reseller::where('id', Auth::user()->reseller_id)->first();
            if ($reseller && $reseller->sms_rate_id) {
                $sms_rate = Rate::where('id', $reseller->sms_rate_id)->first();
            }
            if ($reseller && $reseller->email_rate_id) {
                $email_rate = Rate::where('id',$reseller->email_rate_id)->get();
            }
        }

        if($sms_rate && is_object($sms_rate)){
            $maskingTotal = $sms_rate->selling_masking_rate * $request->masking_sms;
            $nonMaskingTotal = $sms_rate->selling_nonmasking_rate * $request->non_masking_sms;
        } else {
            $maskingTotal = $nonMaskingTotal = 0;
        }

        if($email_rate && is_object($email_rate)) {
            $emailTotal = $email_rate->email_rate * $request->email;
        } else {
            $emailTotal = 0;
        }

        $total = $maskingTotal + $nonMaskingTotal + $emailTotal;

        $remain = $userWallet->available_balance - $total;

        if ($remain < 0) {
            Session::flash('message', 'Insufficient balance for this bundle!');
            Session::flash('m-class', 'alert-danger');
            return json_encode(['url' => route('balance.buy.bundle.list')]);
        }

        $walletData = [
           'available_balance' => $remain,
           'email_balance' => $userWallet->email_balance + $request->email,
           'masking_balance' => $userWallet->masking_balance + $request->masking_sms,
           'non_masking_balance' => $userWallet->non_masking_balance + $request->non_masking_sms
        ];

        $bundleData = [
            'user_id' => Auth::user()->id,
            'masking_balance' => $request->masking_sms,
            'non_masking_balance' => $request->non_masking_sms,
            'email_balance' => $request->email,
            'masking_rate' => is_object($sms_rate) ? $sms_rate->selling_masking_rate: 0,
            'non_masking_rate' => is_object($sms_rate)? $sms_rate->selling_nonmasking_rate: 0,
            'email_rate' => is_object($email_rate)? $email_rate->email_rate: 0,
            'total_price' => $total
        ];
        $userWallet->update($walletData);
        BuyBundle::create($bundleData);
        Session::flash('message', 'Bundle Bought Done!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('balance.buy.bundle.list')]);
    }

    public function buyBundleList()
    {
        $title = 'User Transfer List';
        if (Auth::user()->id_user_group == 4){
            $query = BuyBundle::with(['user'])->where("user_id", Auth::user()->id)->orderBy('created_at', 'DESC');
        } else {
            $query = BuyBundle::with(['user'])->whereHas("user", function ($q) {
                $q->where("reseller_id", Auth::user()->reseller_id);
            })->orderBy('created_at', 'DESC');
        }

        $tableHeaders = [
            'id' => "#",
            'user' => 'User Name',
            'masking_rate' => 'Masking Rate',
            'masking_balance' => 'Masking SMS',
            'non_masking_rate' => 'Non Masking Rate',
            'non_masking_balance' => 'Non Masking SMS',
            'total_price' => 'Total Price'
        ];

        if (empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 5), ["reseller"=>'Reseller'], array_slice($tableHeaders, 5));
        }

        if(config('constants.EMAIL_SERVICE')==true) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 5), [
                'email_rate' => 'Email Rate', 'email_balance' => 'Email'], array_slice($tableHeaders, 5));
        }

        if ($this->ajaxDatatable()) {
            return DataTables::of($query)
                ->addColumn('created_at', function ($row) {
                    return '<span>' . $row['created_at'] . '</span>';
                })
                ->addColumn('user', function ($row){
                    return $row['user']->name;
                })
                ->addIndexColumn()
                ->rawColumns(['created_at'])
                ->make(true);
        }

        $ajaxUrl = route('balance.buy.bundle.list');

        return view('backend.pages.balance.buyBundleList', compact('title', 'ajaxUrl', 'tableHeaders'));
    }
}
