<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GuestService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request)
    {
        return GuestService::getGuest($request);

    }
}
