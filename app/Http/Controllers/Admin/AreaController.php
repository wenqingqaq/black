<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Model\Admin\AreaModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class AreaController extends CommonController
{
    //
    public function index()
    {
        $this->setDbWrite();
        return view('admin.area');
    }

    /**
     * 获取省份列表
     */
    public function getProvince(Request $request)
    {
        $this->setDbRead();

        $sort = $request->input('sort');
        $order = $request->input('order');
        $rows = $request->input('rows');
        $page = $request->input('page');
        $name = $request->input('name');
        $start = ($page-1)*$rows;

        $db = DB::table('setting_province');
        if (!empty($name)) $db = $db->where('province','like','%'.$name.'%');
        if(!empty($order)) $db = $db->orderBy($sort,$order);
        if(!empty($limit)) $db = $db->limit($start,$rows);
        $return = $db->get();
        $count = DB::table('setting_province')->where('province','like','%'.$name.'%')->count();

        echo json_encode ( array (
            'total' => $count,
            'rows' => $return
        ) );
    }

    /**
     * 获取城市列表
     * @param Request $request
     * create by wenQing
     */
    public function getCity(Request $request)
    {
        $this->setDbRead();

        $sort = $request->input('sort');
        $order = $request->input('order');
        $rows = $request->input('rows');
        $page = $request->input('page');
        $provinceId = $request->input('province_id');
        $start = ($page-1)*$rows;

        $db = DB::table('setting_city');
        if (!empty($provinceId)) $db = $db->where('province_id','=',$provinceId);
        if(!empty($order)) $db = $db->orderBy($sort,$order);
        if(!empty($limit)) $db = $db->limit($start,$rows);
        $return = $db->get();
        $count = DB::table('setting_city')->where('province_id','=',$provinceId)->count();

        echo json_encode ( array (
            'total' => $count,
            'rows' => $return
        ) );
    }

    public function getRegion(Request $request)
    {
        $this->setDbRead();

        $sort = $request->input('sort');
        $order = $request->input('order');
        $rows = $request->input('rows');
        $page = $request->input('page');
        $cityId = $request->input('city_id');
        $start = ($page-1)*$rows;

        $db = DB::table('setting_region');
        if (!empty($cityId)) $db = $db->where('city_id','=',$cityId);
        if(!empty($order)) $db = $db->orderBy($sort,$order);
        if(!empty($limit)) $db = $db->limit($start,$rows);
        $return = $db->get();
        $count = DB::table('setting_region')->where('city_id','=',$cityId)->count();

        echo json_encode ( array (
            'total' => $count,
            'rows' => $return
        ) );
    }
}
