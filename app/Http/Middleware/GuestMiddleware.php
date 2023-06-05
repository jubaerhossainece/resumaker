<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class GuestMiddleware
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
        app()->singleton('auth_user', function () use ($request) {
            $user = auth()->user();
            if(!$user){
                if($request->hasHeader('guest-id') && $request->header('guest-id')){
                    return User::where('guest_id', $request->header('guest-id'))->firstOrFail();
                }
            }

            return $user;
        });

        return $next($request);
    }
}
