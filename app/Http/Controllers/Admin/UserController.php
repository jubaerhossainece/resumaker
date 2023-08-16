<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(){
        return view('admin.users.index');
    }


    public function list(){
        $users = User::select('id', 'name', 'email', 'profession', 'country', 'phone', 'image', 'is_active');

        return DataTables::eloquent($users)
        ->addColumn('image', function($user){
            $image_url = Storage::disk('public')->url('/images/user/'.$user->image);
            return $user->image ? "<img src='".$image_url."' alt='' style='height:50px;width:50px;border-radius:50%'>" : '';
        })
        ->addColumn('status', function($user){
            return $user->is_active ? "<span class='text-success'><i class='mr-2 fas fa-circle fa-xs'></i>Active</span>" : "<span class='text-danger'><i class='mr-2 fas fa-circle fa-xs'></i>Inactive</span>";
        })
        ->addColumn('action','components.user-status')
        ->rawColumns(['status', 'action', 'image'])
        ->addIndexColumn()
        ->toJson();
    }
}
