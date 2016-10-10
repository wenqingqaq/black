<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Model\Admin\AreaModel;
use App\Server\Admin\AreaService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    /**
     * 添加区域
     * create by wenQing
     */
    public function addRegion(Request $request)
    {
        $this->setDbWrite ();
        dd($request->input('city_id'));
        echo json_encode([
            'message' => 'test',
            'status' => 1
        ]);
        die;
        $service = new AreaService();
        try
        {
            $return = $service->addRegion(I('post.'));

            //重新生成区域js文件 暂时不需要生成js文件
            $service -> generateRegionJs();
        } catch(HttpException $e){
            $this->ajaxReturn ( array (
                'message' => $e->getMessage (),
                'status' => 0
            ) );
        }

        $this->ajaxReturn ( array (
            'message' => $return ['info'],
            'status' => $return ['status']
        ) );
    }
}
