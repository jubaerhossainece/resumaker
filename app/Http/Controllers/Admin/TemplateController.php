<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index() {
        return view('admin.template.index');
    }


    public function  list() {
        $templates = Template::all();
    }
}
