<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index(){
        return view('admin.admins.index');
    }


    public function list(){
        $admins = Admin::select('id', 'name', 'email', 'image', 'is_active');

        return DataTables::eloquent($admins)
        ->addColumn('image', function($admin){
            $image_url = Storage::disk('public')->url('/images/admin/'.$admin->image);
            return $admin->image ? "<img src='".$image_url."' alt='' style='height:50px;width:50px;border-radius:50%'>" : '';
        })
        ->addColumn('name', function($admin){
            return $admin->name;
        })
        ->addColumn('email', function($admin){
            return $admin->email;
        })
        ->addColumn('status', function($admin){
            return $admin->is_active ? "<span class='text-success'><i class='mr-2 fas fa-circle fa-xs'></i>Active</span>" : "<span class='text-danger'><i class='mr-2 fas fa-circle fa-xs'></i>Inactive</span>";
        })
        ->addColumn('action', 'components.status')
        ->rawColumns(['status', 'action', 'image'])
        ->addIndexColumn()
        ->toJson();
    }
}
