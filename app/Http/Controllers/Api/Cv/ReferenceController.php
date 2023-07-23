<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReferenceResource;
use App\Models\CvUser;
use App\Models\Reference;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    public function get($id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->with('references')->firstOrFail();
        return successResponseJson(ReferenceResource::collection($cv->references));
    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'current_organization' => 'required|string',
            'designation' => 'sometimes|string',
            'phone' => ['required', new PhoneNumber()],
            'email' => 'required|email',
        ]);
        
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();

        $reference = new Reference();
        $reference->name = $request->name;
        $reference->current_organization = $request->current_organization;
        $reference->designation = $request->designation;
        $reference->phone = $request->phone;
        $reference->email = $request->email;
        $data = $cv->references()->save($reference);

        return successResponseJson(new ReferenceResource($data), 'Your reference information saved in database');
    }


    public function update(Request $request, $id, $ref_id)
    {
        $request->validate([
            'name' => 'required|string',
            'current_organization' => 'required|string',
            'designation' => 'sometimes|string',
            'phone' => ['required', new PhoneNumber()],
            'email' => 'required|email',
        ]);
        
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();

        $reference = $cv->references()->findOrFail($ref_id);
        $reference->name = $request->name;
        $reference->current_organization = $request->current_organization;
        $reference->designation = $request->designation;
        $reference->phone = $request->phone;
        $reference->email = $request->email;
        $result = $reference->save();
 
        if($result){
            return successResponseJson(new ReferenceResource($reference), 'Your reference information updated');
        }
        return errorResponseJson('Something went wrong', 500);
    }

    public function destroy($id, $ref_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();

        $reference = $cv->references()->findOrFail($ref_id);
        $reference->delete();

        return successResponseJson(ReferenceResource::collection($cv->references()->get()), 'Your reference information deleted');
    }
}