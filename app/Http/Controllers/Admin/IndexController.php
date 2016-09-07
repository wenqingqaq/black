<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class IndexController extends CommonController
{
    //
    public function index()
    {
        return view('admin.index');
    }

    public function info()
    {
        return view('admin.info');
    }

    public function quit()
    {
        session(['user'=>null]);
        return redirect('admin.login');
    }

    /**
     * 返回菜单显示数据
     * @return mixed
     * create by wenQing
     */
    public function getMenuList()
    {
        $menu = config('menu');
        $result['one'] = [];
        $result['two'] = [];

        if (empty($menu))
        {
            $result = [];
        }
        else
        {
            $i = 0;
            foreach ($menu as $k1 => $v1)
            {
                // 第一层菜单
                $result['one'][] = [
                    'meid'     => $k1,
                    'nickname' => $v1['MName'],
                    'icon' => $v1['iconPath'],
                    'color' => '#ff834c'
                ];
                foreach ($v1['menulist'] as $k2 => $v2)
                {
                    $i++;
                    // 第三层菜单，作为对应第二层菜单的子菜单
                    $child = [];
                    foreach ($v2['menulist'] as $k3 => $v3)
                    {
                        $child[] = [
                            'text' => $v3['MName'],
                            'id'   => $v3['Url'].'?mid='.$v3['MID']
                        ];
                    }
                    // 第二层菜单，与第一层菜单对应
                    $result['two']['menu' . $k1][] = [
                        'meid' => $i,
                        'mname' => $v2['MName'],
                        'children' => $child
                    ];
                }
            }
        }

        return Response::json($result);
    }
}
