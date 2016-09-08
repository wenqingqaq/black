<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    /**
     * 返回json数据
     * @param $result
     * @return mixed
     * create by wenQing
     */
    public function ajaxReturn($result)
    {
        return Response::json($result);
    }
}
