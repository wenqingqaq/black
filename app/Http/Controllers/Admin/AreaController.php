<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;

class AreaController extends CommonController
{
    //
    public function index()
    {
        return view('admin.area');
    }
}
