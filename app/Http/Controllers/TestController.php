<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request)
    {
        dd($request->headers->all());

        // return $userAgent = $request->header('User-Agent');
        return $request->userAgent();
    // return $url = $request->server();
    }
}
