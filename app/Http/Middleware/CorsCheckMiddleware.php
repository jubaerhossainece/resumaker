<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //When in production
        //$allowedOrigins = [
        //            'https://stage-front.mylearning101.co.uk',
        //            'https://mylearning101.co.uk/',
        //            'http://stage-front.mylearning101.co.uk',
        //            'http://mylearning101.co.uk/',
        //        ];
        //        $origin = $_SERVER['HTTP_ORIGIN'];
        //        if (in_array($origin, $allowedOrigins)) {
        //            return $next($request)
        //                ->header('Access-Control-Allow-Origin', $origin)
        //                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        //                ->header('Access-Control-Allow-Headers', 'Content-Type');
        //        }
        //        return $next($request);
            
            //When in local
            return $next($request)->withHeaders([
                'Access-Control-Allow-Origin' => '*',
            ]);
    }
}
