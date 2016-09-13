<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Model\Admin\AreaModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

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
    public function getProvinceAction(Request $request)
    {
        $this->setDbRead();
        $service = new AreaModel();

        $sort = Input::post('sort');
        $order = Input::post('order');
        $rows = Input::post('rows');
        $page = Input::post('page');
        $name = Input::post('name');

        $where = '1=1 ';
        if (! empty ( $name )) {
            $where .= " and province like '%{$name}%'";
        }
        $order = "{$sort} {$order}";
        $start = ($page - 1) * $rows;
        $limit = "{$start},{$rows}";

        $return = $service->getSomeProvinceItemForPage ( $where, '*', $limit, $order );

        echo json_encode ( array (
            'total' => $return ['data'] ['count'],
            'rows' => $return ['data'] ['data']
        ) );
    }
}
