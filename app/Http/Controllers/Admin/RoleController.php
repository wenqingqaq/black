<?php
/**
 * Created by PhpStorm.
 * User: wenQing
 * Date: 2016/10/10
 * Time: 13:57
 */
namespace App\Http\Controllers\Admin;

use App\Server\Admin\AuthorityService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoleController extends CommonController
{
    /**
     * 角色列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * create by wenQing
     */
    public function index()
    {
        return view('role.index');
    }

    /**
     * 获取角色列表
     * @param Request $request
     * create by wenQing
     */
    public function getRoleList(Request $request)
    {
        $this->setDbRead();
        try
        {
            $service = new AuthorityService();
            $page = $request->input('page');
            $rows = $request->input('rows');
            $start = ($page - 1) * $rows;
            $limit = "{$start},{$rows}";
            $role_type = 0;
            $roleData = $service->getRoleList($role_type, $limit);
            $count = $service->getRoleList($role_type);
        }
        catch (HttpException $e)
        {
            $this->exceptionAjaxReturn($e);
        }
        $this->ajaxReturn(['total' => count($count), 'rows' => $roleData]);
    }
}