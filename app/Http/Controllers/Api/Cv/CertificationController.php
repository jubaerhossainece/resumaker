<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class CertificationController extends Controller
{
    public function get($id, $certification)
    {
        $user = auth()->user();
        // return $certifications = CvUser::where('id', $id)->get();
        // $certifications = DB::table('cv_users')->whereJsonContains('certifications', ['id' => $certification])->select('certifications')->first();
        $certifications = DB::table('cv_users')->where('certifications->id', $certification)->select('certifications')->first();
        return json_decode($certifications->certifications);

        $certifications = CvUser::where([
            'id' => $id,
            'user_id' => $user->id,
            'certifications->id' => $certification
        ])->select('education', 'user_id', 'id')->first();

        return successResponseJson($certifications);

    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'issuing_org' => 'required|string',
            'credential_url' => 'required|string',
            'issue_date' => 'required|date',
            'exp_date' => 'required|date',
            'is_no_exp' => 'required|boolean',
        ]);
        
        $user = auth()->user();
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => $user->id,
        ])->first();
        // return gettype($cv->certifications);
        
        if($cv){
            $cv->user_id = $user->id;

            $certification = new stdClass;
            $certification->id = uniqid();
            $certification->name = $request->name;
            $certification->issuing_org = $request->issuing_org;
            $certification->credential_url = $request->credential_url;
            $certification->issue_date = $request->issue_date;
            $certification->exp_date = $request->exp_date;
            $certification->is_no_exp = $request->is_no_exp;

            $prev_data = json_decode($cv->certifications) ?? new stdClass;
            $new_data = $certification;

            $cv->certifications = json_encode((object)array_merge((array)$prev_data, (array)$new_data));
            $cv->save();
            // if(is_null($cv->certifications)){
            //     $cv->certifications[uniqid()] = $certification;
            // }else{
            //     $cv->certifications[uniqid()] = $certification;
            //     array_merge();
            // }
            return $cv->certifications;
            return successResponseJson($cv, 'Your certification information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
        }
    }


    public function update(Request $request, $id, $certification)
    {
        $request->validate([
            'name' => 'required|string',
            'issuing_org' => 'required|string',
            'credential_url' => 'required|string',
            'issue_date' => 'required|date',
            'exp_date' => 'required|date',
            'is_no_exp' => 'required|boolean',
        ]);
        
        // $cv = DB::table('cv_users')->where('certifications->id', $certification)->update('certifications')->first();
        
        if($cv){

            $certification = new stdClass;
            $certification->name = $request->name;
            $certification->issuing_org = $request->issuing_org;
            $certification->credential_url = $request->credential_url;
            $certification->issue_date = $request->issue_date;
            $certification->exp_date = $request->exp_date;
            $certification->is_no_exp = $request->is_no_exp;
            
            $prev_data = $cv->certifications ?? [];
            $new_data = [
                $certification => $certification
            ];
            $merged_data = array_merge($prev_data, $new_data);
            $cv->certifications = $merged_data;
            $cv->save();
            return $cv->certification;
    
            return successResponseJson($cv, 'Your certification information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
        }
    }
}
