<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Http\Requests;

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

    /**
     * 失败的json返回数据
     * @param $data
     * @param string $msg
     * @return mixed
     * create by wenQing
     */
    public function errorReturn($msg = '',$data = [])
    {
        return Response::json([
            'type' => 'error',
            'data' => $data ? $data : [],
            'msg' => $msg ? $msg : 'error'
        ]);
    }

    /**
     * 成功的json返回数据
     * @param $data
     * @param string $msg
     * @return mixed
     * create by wenQing
     */
    public function successReturn($msg = '',$data = [])
    {
        return Response::json([
            'type' => 'suc',
            'data' => $data ? $data : [],
            'msg' => $msg ? $msg : 'success'
        ]);
    }
}
