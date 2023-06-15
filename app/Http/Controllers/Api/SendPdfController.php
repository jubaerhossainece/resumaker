<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SendPdfController extends Controller
{
    public function sendToMail(Request $request)
    {
        $user = auth('sanctum')->user();
        $data['email'] = $user->email;
        // $pdfContent = $request->file('pdf');

        // return $pdffile = base64_encode($request->file('pdf'));
        // $pdfContent = base64_decode($pdffile);
        $pdfContent = base64_decode($request->pdf);
        $filename = Str::random(10).time().'.'.'pdf';
        $storagePath = storage_path('app/public/pdf/') . $filename;
        file_put_contents($storagePath, $pdfContent); 
        // $pdfPath = $request->file('pdf')->storeAs('pdf', $filename);

        
        // Storage::putFileAs('pdf', $pdfContent, $filename);
        // Mail::send('admin.pdf.file', $data, function($message) use($data, $pdfContent){
        //     $message->to($data['email'])
        //     ->subject('Your cv/ resume file.')
        //     ->attach($pdfContent);
        // });

    }
}
