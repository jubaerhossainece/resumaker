<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class AuthUserMiddleware
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
                if($request->has('guest_id') && $request->guest_id){
                    return $user = User::where('guest_id', $request->guest_id)->firstOrFail();
                }
                return errorResponseJson('No data found', 422);
            }

            return $user;
        });

        return $next($request);
    }
}
