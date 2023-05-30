<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use App\Models\Reference;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->with('references')->firstOrFail();
        return successResponseJson($cv->references);
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
        
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();

        $reference = new Reference();
        $reference->name = $request->name;
        $reference->current_organization = $request->current_organization;
        $reference->designation = $request->designation;
        $reference->phone = $request->phone;
        $reference->email = $request->email;
        $cv->references()->save($reference);

        return successResponseJson($cv->references()->findOrFail($reference->id), 'Your reference information saved in database');
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
        
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();

        $reference = $cv->references()->findOrFail($ref_id);
        $reference->name = $request->name;
        $reference->current_organization = $request->current_organization;
        $reference->designation = $request->designation;
        $reference->phone = $request->phone;
        $reference->email = $request->email;
        $reference->save();

        return successResponseJson($cv->references()->findOrFail($ref_id), 'Your reference information updated');
    }

    public function destroy($id, $ref_id)
    {
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();

        $reference = $cv->references()->findOrFail($ref_id);
        $reference->delete();

        return successResponseJson($cv->references()->get(), 'Your reference information deleted');
    }
}