<?php
/**
 * Created by PhpStorm.
 * User: wenQing
 * Date: 2016/10/10
 * Time: 13:57
 */
namespace App\Http\Controllers\Admin;

use App\Model\Admin\Role;
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
     * @return mixed
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
            $role_type = 0;
            $role = $service->getRoleList($role_type, $start,$rows);
            $count = $role['count'];
            $roleData = $role['data'];
        }
        catch (HttpException $e)
        {
            return $this->errorReturn($e->getMessage());
        }

        return $this->ajaxReturn(['total' => count($count), 'rows' => $roleData]);
    }

    /**
     * 获取权限列表
     * @param Request $request
     * @return mixed
     * create by wenQing
     */
    public function getAuthList(Request $request)
    {
        $this->setDbRead();
        try
        {
            $service = new AuthorityService ();
            $menuData = $service->getMenuList($request->input('role_id'), 0);
        }
        catch (HttpException $e)
        {
            return $this->errorReturn($e->getMessage());
        }
        return $this->ajaxReturn($menuData);
    }

    /**
     * 添加角色
     * @param Request $request
     * @return mixed
     * create by wenQing
     */
    public function add(Request $request)
    {
        $this->setDbWrite();
        try
        {
            $role_type = ROLE_TYPE_PLATFORM;
            $service = new AuthorityService ();
            $user_info = $request->session()->get('user_info');
            $service->addRole($request->role, $request->remark, $role_type, $user_info ['uid']);
        }
        catch (HttpException $e)
        {
            return $this->errorReturn($e->getMessage());
        }
        return $this->successReturn("添加成功！");
    }

    /**
     * 编辑角色
     */
    public function edit(Request $request)
    {
        $this->setDbWrite();
        try
        {
            $user_info = $request->session()->get('user_info');
            Role::where('role_id',$request->role_id)->update([
                'role' => $request->role,
                'remark' => $request->remark,
                'uid_owner' => $user_info ['uid']
            ]);
        }
        catch (HttpException $e)
        {
            return $this->errorReturn($e->getMessage());
        }
        return $this->successReturn("编辑成功！");
    }

    /**
     * 删除角色
     */
    public function delete(Request $request)
    {
        $this->setDbWrite();

        Role::where('role_id',$request->role_id)->delete();
        return $this->successReturn("删除成功！");
    }

    /**
     * 保存角色权限
     */
    public function saveRoleAuthority(Request $request)
    {
        $this->setDbWrite();
        try
        {
            $service = new AuthorityService ();
            $service->saveRoleAccess($request->roleId, $request->access);
        }
        catch (HttpException $e)
        {
            return $this->errorReturn($e->getMessage());
        }
        return $this->successReturn("保存角色权限成功！");
    }
}