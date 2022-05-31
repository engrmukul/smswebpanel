<?php

namespace App\Http\Controllers;

use App\Classes\SmsCount;
use App\Models\SenderId;
use App\Models\Sentmessage;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Http\Request;

class ApiMessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $smsCount = new SmsCount();
        $error_sms = [];
        $totalSmsCount = 0;

        $maskSmsCount = 0;
        $nonMaskSmsCount = 0;

        $sms_type = '';

        $pattern = "/(^(\+8801|8801|01|008801))[1|3-9]{1}(\d){8}$/";

        if ($request->isMethod('get')) {
            // apiKey, maskName, recipients, message
            $sms_data = null;
            $api_key = $request->api_key;
            $mask_name = $request->senderid;
            $recipients = $request->contacts;
            $message = $request->msg;
            $fileName = null;
            $source = 'API-GET';

            if (empty($recipients)) {
                return $this->return_response('', 'Failed', 'Please insert recipient');
            }

            if (empty($message)) {
                return $this->return_response('', 'Failed', 'Message can\'t be empty');
            }

            $explodeRecipients = explode(',', $recipients);
            $numbers = [];
            foreach ($explodeRecipients as $explodeRecipient) {
                if (preg_match($pattern, $explodeRecipient)){
                    $numbers[] = $explodeRecipient;
                } else {
                    $error_sms[] = [
                        'wrong-recipient' => '('.$explodeRecipient.') is wrong recipient. Message can\'t send to this recipient'
                    ];
                }
            }
            $totalNumber = count($numbers);
            $recipients = implode(',', $numbers);
            $smsInfo = $smsCount->countSms($message);
            $countSms = $smsInfo->count;
            //Only unicode Sms Accept
//            if ($sms_type != 'unicode') {
//                return $this->return_response('', 'Failed','Only Unicode SMS Accepted!');
//            }

            if ($mask_name) {
                $mask = SenderId::where('senderID', $mask_name)->first();
                if (!empty($mask)) {
                    $mask_id = $mask->senderID;
                    $maskSmsCount = $totalNumber * $countSms;
                } else {
                    return $this->return_response('', 'Failed', 'Wrong Sender ID');
                }

            } else {
                $mask_id = '';
                $nonMaskSmsCount = $totalNumber * $countSms;
            }


            if (empty($api_key)) {
                return $this->return_response('', 'Failed', 'Plead insert API Key');
            }

            $user = User::where('APIKEY', $api_key)->first();

            if ((empty($user->reseller_id) && $user->id_user_group == 4) || !empty($user->reseller_id)) {
                $userWallet = UserWallet::where('user_id', $user->id)->first();
                if ($mask_id != '' && $totalSmsCount > $userWallet->masking_balance) {
                    return $this->return_response('', 'Failed', 'Insufficient Masking Balance');
                }

                if ($mask_id == '' && $totalSmsCount > $userWallet->non_masking_balance) {
                    return $this->return_response('', 'Failed', 'Insufficient Non Masking Balance');
                }
            }

        } else {

            $mask_id = null;
            $recipients = null;
            $message = null;
            $api_key = $request->api_key;
            $sms_data = $request->msg;
            $source = 'API-POST';

            if (empty($sms_data)) {
                return $this->return_response('', 'Failed', 'Empty Body. Please check your input data');
            }


            if (count($sms_data)>200) {
                return $this->return_response('', 'Failed', 'You Can Send Maximum 200 Messages In a Single Request');
            }
            $sms_data_new = [];

            foreach ($sms_data as $sms) {
                if(preg_match($pattern, $sms['recipient'])){
                    $mask_error = '';
                    if (isset($sms['mask'])){
                        if ($sms['mask'] != '' || $sms['mask'] != null) {
                            $mask = SenderId::where('senderID', $sms['mask'])->first();
                            if (empty($mask)) {
                                $error_sms[] = [
                                    'wrong-mask' => '('.$sms['mask'].') is wrong sender id'
                                ];
                                $mask_error = 'Yes';
//                                return $this->return_response('', 'Failed', 'Wrong Sender ID');
                            }
                            $maskSmsCount += $smsCount->countSms($sms['message'])->count;
                        } else {
                            $nonMaskSmsCount += $smsCount->countSms($sms['message'])->count;
                        }
                    }
                    $smsInfo = $smsCount->countSms($sms['message']);

                    //Only unicode Sms Accept
//                if ($smsInfo->smsType == 'unicode') {
//                    $sms_type = 'unicode';
//                } else {
//                    return $this->return_response('', 'Failed','Only Unicode SMS Accepted!');
//                }

                    if($mask_error == ''){
                        $sms_data_new[] = [
                            "recipient"=>$sms['recipient'],
                            "mask"=>$sms['mask'],
                            "message"=>$sms['message']
                        ];
                    }
                } else {
                    $error_sms[] = [
                        'wrong-recipient' => '('.$sms['recipient'].') is wrong recipient. Message can\'t send to this recipient'
                    ];
                }
            }

            if(empty($sms_data_new)){
                return $this->return_response('', 'Failed', 'Wrong Data');
            }

            $sms_data = json_encode($sms_data_new);
            $fileName = md5($api_key . time()) . ".json";
            $file_path = 'assets/uploads/messages/' . $fileName;
            $fp = fopen($file_path, 'w');
            fwrite($fp, $sms_data);

            fclose($fp);
        }

        if (empty($api_key)) {
            return $this->return_response('', 'Failed', 'Plead insert API Key');
        }

        $user = User::where('APIKEY', $api_key)->first();
        if(empty($user)){
            return $this->return_response('', 'Failed','Unauthorised Access, please check your API Key');
        }

        if($user->status != 'ACTIVE') {
            return $this->return_response('', 'Failed','Unauthorised Access, User Inactive Please Contact With Support');
        }

        if(!empty($user->reseller_id)) {
            if($user->reseller->status != 'ACTIVE'){
                return $this->return_response('', 'Failed','Unauthorised Access, User Inactive Please Contact With Support');
            }
        }

        if ((empty($user->reseller_id) && $user->id_user_group == 4) || !empty($user->reseller_id)) {
            $userWallet = UserWallet::where('user_id', $user->id)->first();
            if ($maskSmsCount > $userWallet->masking_balance) {
                return $this->return_response('', 'Failed', 'Insufficient Masking Balance');
            }

            if ($nonMaskSmsCount > $userWallet->non_masking_balance) {
                return $this->return_response('', 'Failed', 'Insufficient Non Masking Balance');
            }
        }

        $orderid = $user->id . time();
        $api_data = array(
            'user_id' => $user->id,
            'message' => $message,
            'recipient' => $recipients,
            'senderID' => $mask_id,
            'date' => date('Y-m-d h:i:s'),
            'source' => 'API',
            'sms_count' => $maskSmsCount+$nonMaskSmsCount,
            'IP' => \Request::getClientIp(),
            'sms_type' => 'sendSms',
            'orderid' => $orderid,
            'file' => $fileName,
            'is_unicode' => 1,
            'status'=>'Queue'
        );

        $sendmessage_id = Sentmessage::create($api_data)->id;
        if ($sendmessage_id) {
            return $this->return_response($orderid, 'Success', 'Request has been accepted successfully', $error_sms);
        } else {
            return $this->return_response('', 'Failed', 'Please check your request data!');
        }
    }

    public function return_response($msg_id, $staus, $msg, $error=null)
    {
        $sms['messageid'] = $msg_id;
        $sms['status'] = $staus;
        $sms['message'] = $msg;
        if ($error != null){
            $sms['warning'] = $error;
        }
        header("content_type: application/json", True);
        echo json_encode($sms);
    }


    /**
     * @param $apikey
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBalance($apikey){

        $user = User::where('APIKEY', $apikey)->first();

        if (isset($user) AND !empty($user)) {
            $user = $user->toArray();
            $balance = UserWallet::where('user_id', $user['id'])->first();

            if (isset($balance) AND !empty($balance)) {
                $balance = $balance->toArray();
                return response()->json($balance, '200', ['content_type: application/json']);
            } else {
                return response()->json(array('Please check your request data!'),'400', ['content_type: application/json']);
            }
        }else{
            return response()->json(array('Please check your request data!'),'400', ['content_type: application/json']);
        }
    }

    /**
     * @param $apikey
     * @param $SMS_SHOOT_ID
     * @return void
     */
    public function getDLR($apikey, $SMS_SHOOT_ID){
        echo $apikey.$SMS_SHOOT_ID;
    }

    /**
     * @param $username
     * @param $password
     * @return \Illuminate\Http\JsonResponse
     */
    public function getkey($username, $password){
        $user = User::where(['username'=>$username, 'password'=>$password])->first();

        if (isset($user) AND !empty($user)) {
            $user = $user->toArray();
            return response()->json($user, '200', ['content_type: application/json']);
        } else {
            return response()->json(array('Please check your request data!'),'400', ['content_type: application/json']);
        }
    }
}
