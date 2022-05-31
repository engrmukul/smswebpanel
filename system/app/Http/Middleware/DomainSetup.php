<?php

namespace App\Http\Middleware;

use App\Models\SiteConfig;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request As UrlRequest;
use Illuminate\Support\Facades\View;

class DomainSetup
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
        $url = UrlRequest::getHttpHost();
        $config = SiteConfig::where('site_url', $url)->first();

        if(!$config){
            $config = SiteConfig::where('site_url', null)->first();
        }

        $result = $config;
        View::share('config', $result);
        date_default_timezone_set("Asia/Dhaka");

        return $next($request);
    }
}
