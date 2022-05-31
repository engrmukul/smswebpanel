<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $roles = $this->getRequiredRoleForRoute($request->route());

        if (!empty($roles)) {
            $thisMenu = Menu::where('route_name', $roles)->first();

            if(empty($thisMenu)){
                $user_group_ids = explode('.',$roles);
                $parentMenu = Menu::where('active_route', $user_group_ids[0])->where('parent_id', 0)->first();
                if (!empty($parentMenu)) {
                    if ($parentMenu->status == 'Inactive') {
                        Session::flash('message', 'You Have Not Permission To View This Page!');
                        Session::flash('m-class', 'alert-danger');
                        return redirect()->back();
                    }
                    $menuGroupIds = explode(',', $parentMenu->user_group_id);
                }
                else {
                    return $next($request);
                }

            } else {
                if($thisMenu->status == 'Inactive' or ($thisMenu->parent_id != 0)? $thisMenu->parent->status == 'Inactive' : ''){
                    Session::flash('message', 'You Have Not Permission To View This Page!');
                    Session::flash('m-class', 'alert-danger');
                    return redirect()->back();
                }
                $menuGroupIds = explode(',',$thisMenu->user_group_id);
            }

            if (in_array(Auth::user()->id_user_group, $menuGroupIds)) {
                return $next($request);
            } else {
                Session::flash('message', 'You Have Not Permission To View This Page!');
                Session::flash('m-class', 'alert-danger');
                return redirect()->back();
            }
        } else {
            return $next($request);
        }
    }

    private function getRequiredRoleForRoute($route){
        $action = $route->getAction();
        return isset($action['as']) ? $action['as'] : null;
    }
}
