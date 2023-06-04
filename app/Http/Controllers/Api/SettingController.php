<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function advertisement()
    {
        $adv = Setting::where('name', 'advertisement')->firstOrFail();
        unset($adv->created_at,$adv->updated_at,$adv->id);
        return successResponseJson($adv);
    }
}
