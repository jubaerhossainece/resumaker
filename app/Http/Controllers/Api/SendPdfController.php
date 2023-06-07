<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SendPdfController extends Controller
{
    public function sendToMail(Request $request)
    {
        $pdfContent = base64_decode($request->pdf);
        // Storage::put(filePath, $contents);
    }
}
