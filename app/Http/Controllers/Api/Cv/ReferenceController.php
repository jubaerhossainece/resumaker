<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use Illuminate\Http\Request;
use stdClass;

class ReferenceController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->select('references', 'user_id', 'id')->first();

        if($cv){
            return successResponseJson($cv->references);
        }else{
            return errorResponseJson('No cv found.', 422);
        }
    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'current_organization' => 'required|string',
            'designation' => 'sometimes|string',
            'phone' => 'required|alpha_num',
            'email' => 'required|email',
        ]);
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $reference = new stdClass;
            $reference->name = $request->name;
            $reference->current_organization = $request->current_organization;
            $reference->designation = $request->designation;
            $reference->phone = $request->phone;
            $reference->email = $request->email;

            $id = uniqid();
            if(is_null($cv->references)){
                $arr = array();
                $arr[$id] = $reference;
                $cv->references = $arr;
                $cv->save();
            }else{
                $arr = array();
                $arr[$id] = $reference;
                
                $new_array = array_merge($cv->references, $arr);
                $cv->references = $new_array;
                $cv->save();
            }
    
            return successResponseJson($cv->references, 'Your publication information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
        }
    }


    public function update(Request $request, $id, $ref_key)
    {
        $request->validate([
            'name' => 'required|string',
            'current_organization' => 'required|string',
            'designation' => 'sometimes|string',
            'phone' => 'required|alpha_num',
            'email' => 'required|email',
        ]);
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($cv){
            $reference = new stdClass;
            $reference->name = $request->name;
            $reference->current_organization = $request->current_organization;
            $reference->designation = $request->designation;
            $reference->phone = $request->phone;
            $reference->email = $request->email;

            $reference_list = $cv->references;
            $reference_list[$ref_key] = $reference;
            $cv->references = $reference_list;
            $cv->save();

            return successResponseJson($cv->references, 'Your reference information updated');
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }

    public function destroy($id, $ref_key)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $reference_list = $cv->references;
            unset($reference_list[$ref_key]);
            $cv->references = $reference_list;
            $cv->save();
            return successResponseJson($cv->references, 'Your reference information deleted');
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }
}