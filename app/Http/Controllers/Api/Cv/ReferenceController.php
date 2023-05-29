<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use App\Models\Reference;
use Illuminate\Http\Request;
use stdClass;

class ReferenceController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->with('references')->first();

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
            $reference = new Reference();
            $reference->name = $request->name;
            $reference->current_organization = $request->current_organization;
            $reference->designation = $request->designation;
            $reference->phone = $request->phone;
            $reference->email = $request->email;
            $cv->references()->save($reference);
    
            return successResponseJson($reference, 'Your reference information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
        }
    }


    public function update(Request $request, $id, $ref_id)
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
            $reference = $cv->references()->find($ref_id);
            $reference->name = $request->name;
            $reference->current_organization = $request->current_organization;
            $reference->designation = $request->designation;
            $reference->phone = $request->phone;
            $reference->email = $request->email;
            $result = $reference->save();
            if($result){
                return successResponseJson($cv->references, 'Your reference information updated');
            }else{
                return errorResponseJson('Something went wrong.', 422);
            }

        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }

    public function destroy($id, $ref_id)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($cv){
            $reference = $cv->references()->find($ref_id);
            if($reference){
                $reference->delete();
                return successResponseJson($cv->references()->get(), 'Your reference information deleted');
            }
            return errorResponseJson('No reference info to delete.', 422);

        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }
}