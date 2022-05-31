<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Domain;
use App\Models\Group;
use App\Models\Menu;
use App\Models\Rate;
use App\Models\Reseller;
use App\Models\SenderId;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    public function channelData(Request $request) {
        $channels = Channel::where('operator_id', $request->id)->get();
        return response()->json($channels);
    }

    public function userDataByMask(Request $request) {
        $mask = SenderId::findOrFail($request->id);
        $users = User::whereNotIn('id_user_group', [1,2,3])->where('reseller_id', $mask->reseller_id)->get();
        return response()->json($users);
    }

    public function userDataByDomain(Request $request) {
        $domain = Domain::findOrFail($request->id);
        $users = User::whereNotIn('id_user_group', [1,2,3])->where('reseller_id', $domain->reseller_id)->get();
        return response()->json($users);
    }

    public function selectUsers(Request $request)
    {
        $sc = $request->searchTerm;
        if(empty($sc)){
            $users = User::where('reseller_id', Auth::user()->reseller_id)->orderBY('id', 'ASC')->limit('5')->get();
        } else {
            $users = User::where('reseller_id', Auth::user()->reseller_id)->where(function($query) use ($sc) {
                $query->where('name', 'like',"%$sc%")
                    ->orWhere('username', 'LIKE', '%'.$sc.'%');
            })->orderBY('id', 'ASC')->limit(5)->get();
        }

        $data = array(array('id' => '', 'text' => 'Select User Name'));
        foreach ($users as $user){
            $data[] = array(
                'id' => $user->id,
                'text' => $user->name.' ('.$user->username.')'
            );
        }
        echo json_encode($data);
    }

    public function selectResellers(Request $request)
    {
        $sc = $request->searchTerm;
        if(empty($sc)){
            $users = Reseller::orderBY('id', 'ASC')->limit('5')->get();
        } else {
            $users = Reseller::where(function($query) use ($sc) {
                $query->where('reseller_name', 'like',"%$sc%");
            })->orderBY('id', 'ASC')->limit(5)->get();
        }

        $data = array(array('id' => '', 'text' => 'Select Reseller Name'));
        foreach ($users as $user){
            $data[] = array(
                'id' => $user->id,
                'text' => $user->reseller_name
            );
        }

        echo json_encode($data);
    }


    public function selectDistrict(Request $request)
    {
        $div_id = $request->div_id;

        $division = findBelongsTo(allDivision(), ['name' => $div_id]);

        $districts = findHasMany(allDistrict(), [
            'division_id' => $division['id']
        ]);

        $data = array(array('id'=> '', 'text' => 'Select District'));
        foreach ($districts as $district){
            $data[] = array(
                'id' => $district['name'],
                'text' => $district['name']
            );
        }

        echo json_encode($data);
    }

    public function selectUpazilla(Request $request)
    {
        $dis_id = $request->dis_id;

        $district = findBelongsTo(allDistrict(), ['name' => $dis_id]);

        $upazillas = findHasMany(allUpazillas(), [
            'district_id' => $district['id']
        ]);

        $data = array(array('id'=> '', 'text' => 'Select Upazilla'));
        foreach ($upazillas as $upazilla){
            $data[] = array(
                'id' => $upazilla['name'],
                'text' => $upazilla['name']
            );
        }

        echo json_encode($data);
    }

    public function getRemainingBalance(Request $request)
    {
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
            $maskingTotal = $sms_rate->selling_masking_rate * $request->msms;
            $nonMaskingTotal = $sms_rate->selling_nonmasking_rate * $request->nsms;
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

        echo $remain;
    }

    public function selectMasks(Request $request)
    {
        $sc = $request->searchTerm;
        if(empty($sc)){
            if (empty(Auth::user()->reseller_id)) {
                $masks = SenderId::get();
            } else {
                $masks = SenderId::where('reseller_id', Auth::user()->reseller_id)->limit('5')->get();
            }
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $masks = SenderId::limit('5')->where(function ($query) use ($sc) {
                    $query->where('senderId', 'like', "%$sc%");
                })->orderBY('id', 'ASC')->limit(5)->get();;
            } else {
                $masks = SenderId::where('reseller_id', Auth::user()->reseller_id)->where(function ($query) use ($sc) {
                    $query->where('senderId', 'like', "%$sc%");
                })->orderBY('id', 'ASC')->limit(5)->get();
            }
        }

        $data = array(array('id' => '', 'text' => 'Select Mask Name'));
        foreach ($masks as $mask){
            $data[] = array(
                'id' => $mask->id,
                'text' => $mask->senderID
            );
        }
        echo json_encode($data);
    }


    public function selectDomains(Request $request)
    {
        $sc = $request->searchTerm;
        if(empty($sc)){
            if (empty(Auth::user()->reseller_id)) {
                $domains = Domain::get();
            } else {
                $domains = Domain::where('reseller_id', Auth::user()->reseller_id)->limit('5')->get();
            }
        } else {
            if (empty(Auth::user()->reseller_id)) {
                $domains = Domain::limit('5')->where(function ($query) use ($sc) {
                    $query->where('domain', 'like', "%$sc%");
                })->orderBY('id', 'ASC')->limit(5)->get();;
            } else {
                $domains = Domain::where('reseller_id', Auth::user()->reseller_id)->where(function ($query) use ($sc) {
                    $query->where('domain', 'like', "%$sc%");
                })->orderBY('id', 'ASC')->limit(5)->get();
            }
        }

        $data = array(array('id' => '', 'text' => 'Select Domain Name'));
        foreach ($domains as $domain){
            $data[] = array(
                'id' => $domain->id,
                'text' => $domain->domain
            );
        }
        echo json_encode($data);
    }

    public function getUserMaskDomain(Request $request){
        $user_id = $request->user_id;
        $senderIds = SenderId::whereRaw("find_in_set($user_id, user_id)" )->where('status', 'Active')->get();
        $domains = Domain::whereRaw("find_in_set($user_id, user_id)" )->where('status', 'Active')->get();

        echo json_encode(['masks' => $senderIds,
            'domains' => $domains]);
    }

    public function getFromEmail(Request $request){
        $domain = $request->domain;
        $from_email = Domain::where('domain', $domain)->first();

        echo $from_email;
    }

    public function getRouteName(Request $request){
        if($request->parent_id) {
            $menu = Menu::findOrFail($request->parent_id);
            $active_route = $menu->active_route;
        } else {
            $active_route = $request->active_route;
        }

        $routes = routeDetails()['routeNames'];
        $datas = [];
        foreach ($routes as $route) {
            if(explode('.', $route)[0] == $active_route){
                $datas[] = $route;
            }
        }

        echo json_encode(['route_name' => $datas, 'active_route' => $active_route]);
    }



    public function selectInvoiceUsers(Request $request)
    {
        $sc = $request->searchTerm;
        if(empty($sc)){
            $users = User::where('reseller_id', Auth::user()->reseller_id)->whereNotNull('sms_rate_id')->orderBY('id', 'ASC')->limit('5')->get();
        } else {
            $users = User::where('reseller_id', Auth::user()->reseller_id)->whereNotNull('sms_rate_id')->where(function($query) use ($sc) {
                $query->where('name', 'like',"%$sc%")
                    ->orWhere('username', 'LIKE', '%'.$sc.'%');
            })->orderBY('id', 'ASC')->limit(5)->get();
        }

        $data = array(array('id' => '', 'text' => 'Select User Name'));
        foreach ($users as $user){
            $data[] = array(
                'id' => $user->id,
                'text' => $user->name.' ('.$user->username.')'
            );
        }
        echo json_encode($data);
    }

    public function getContactGroup(Request $request){
        $sc = $request->searchTerm;

        if(empty($sc)){
            if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
                $groups = Group::where('user_id', Auth::user()->id)->where('type', 'Public')->orderBY('id', 'ASC')->limit('5')->get();
            } else {
                if (empty(Auth::user()->reseller_id)) {
                    $groups = Group::orderBY('id', 'ASC')->limit('5')->get();
                } else {
                    $groups = Group::where('reseller_id', Auth::user()->reseller_id)->where('type', 'Public')->orderBY('id', 'ASC')->limit('5')->get();
                }
            }
//            $users = User::where('reseller_id', Auth::user()->reseller_id)->whereNotNull('sms_rate_id')->orderBY('id', 'ASC')->limit('5')->get();
        } else {
            if (Auth::user()->id_user_group == config('constants.USER_GROUP')) {
                $groups = Group::where('user_id', Auth::user()->id)->where('type', 'Public')->where(function($query) use ($sc) {
                    $query->where('name', 'like',"%$sc%");
                })->orderBY('id', 'ASC')->limit('5')->get();
            } else {
                if (empty(Auth::user()->reseller_id)) {
                    $groups = Group::where(function($query) use ($sc) {
                        $query->where('name', 'like',"%$sc%");
                    })->orderBY('id', 'ASC')->limit('5')->get();
                } else {
                    $groups = Group::where('reseller_id', Auth::user()->reseller_id)->where('type', 'Public')->where(function($query) use ($sc) {
                        $query->where('name', 'like',"%$sc%");
                    })->orderBY('id', 'ASC')->limit('5')->get();
                }
            }
//            $users = User::where('reseller_id', Auth::user()->reseller_id)->whereNotNull('sms_rate_id')->where(function($query) use ($sc) {
//                $query->where('name', 'like',"%$sc%");
//            })->orderBY('id', 'ASC')->limit(5)->get();
        }

        $data = array(array('id' => '', 'text' => 'Select Contact Group'));
        foreach ($groups as $group){
            $data[] = array(
                'id' => $group->id,
                'text' => $group->name
            );
        }
        echo json_encode($data);
    }
}
