<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        if(Cache::get('if_transaction') == 'true')
        {
            DB::rollBack();
        }
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
        if(Cache::get('if_transaction') == 'true')
        {
            DB::commit();
        }
        return Response::json([
            'type' => 'suc',
            'data' => $data ? $data : [],
            'msg' => $msg ? $msg : 'success'
        ]);
    }

    /**
     * 不开启事务
     * create by wenQing
     */
    public function setDbRead()
    {
        Cache::put('if_transaction','false',10);
    }

    /**
     * 开启事务
     * create by wenQing
     */
    public function setDbWrite()
    {
        Cache::put('if_transaction','true',10);
        DB::beginTransaction(); //判断是否开启事务
    }
}
