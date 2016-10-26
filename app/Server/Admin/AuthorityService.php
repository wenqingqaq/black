<?php
/**
 * Created by PhpStorm.
 * User: wenQing
 * Date: 2016/9/8
 * Time: 10:39
 */
namespace App\Server\Admin;

use App\Model\Admin\Access;
use App\Model\Admin\Role;
use App\Model\Admin\RoleUser;
use App\Model\Admin\User;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthorityService
{
    /**
     * 验证用户密码
     */
    public function checkUserPass($user, $pass, $auto, $type_user = '0')
    {
        $result = User::where('user',$user)->get()->toArray();
        if (empty ($result))
        {
            throw new HttpException('200','用户不存在');
        }
        else
        {
            $querySalt = $result [0] ['salt'];
            $queryPass = $result [0] ['pass'];
        }
        if (md5($querySalt . $pass) == $queryPass && false === strpos($type_user, $result [0] ['type_user']))
        {
            if ($result [0] ['status'] == 0)
            {
                throw new HttpException('200','该用户被禁用!');
            }
            elseif ($result [0] ['status'] == 2)
            {
                throw new HttpException('200','该用户不存在!');
            }
            if ($auto == 'on')
            {
                $login_info = ['user' => $user, 'pass' => $queryPass];
                $type_user = $result[0]['type_user'];
                switch ($type_user)
                {
                    case '0':
                        Cookie::queue('platform_login_info',json_encode($login_info),10);
                        break;
                    case '1':
                        Cookie::queue('business_login_info',json_encode($login_info),'365 * 24 * 60');
                        break;
                    case '2':
                        Cookie::queue('store_login_info',json_encode($login_info),'365 * 24 * 60');
                        break;
                }
            }

            return $result [0];
        }
        else
        {
            throw new HttpException('200','用户名或密码错误!');
        }
    }

    /**
     * 获取用户权限
     */
    public function getAllAccessByUid($uid)
    {
        $accessModel = new Access();
        $result = $accessModel->selectAccessByUid($uid);
        foreach ($result as $key => $value)
        {
            $access [] = $value;
        }

        return $access ? $access : [];
    }

    /**
     * 获取权限列表，菜单显示
     * @param $uid
     * @param $isadmin
     * @param int $user_type
     * @return array
     * create by wenQing
     */
    public function getUserAccess($uid, $isadmin, $user_type = 0)
    {
        if ( !$isadmin)
        {
            $roleId = RoleUser::where('uid','=',$uid)->get()->toArray();
            if ($roleId != null)
            {
                $n = '';
                for ($i = 0; $i < count($roleId); $i++)
                {
                    $n .= $roleId [$i] ['role_id'] . ',';
                }
                $n = substr($n, 0, -1);
                $result = Access::whereIn('role_id',[$n])->get()->toArray();
            }
            else
            {
                return false;
            }
        }
        switch ($user_type)
        {
            case 0 :
                $menu = config('menu.MENU_CRM');
                break;
            case 1 :
                $menu = config('menu.MENU_CRM');
                break;
            case 2 :
                $menu = config('menu.MENU_CRM');
                break;
        }
        $arr1 = [];
        $arr2 = [];
        $id = 0;

        foreach ($menu as $k => $v)
        {
            $tmp ['meid'] = $id++;
            $tmp ['nickname'] = $v ['name'];
            $tmp ['icon'] = $v ['cls'];
            $tmp ['color'] = $v ['color'];
            array_push($arr1, $tmp);
        }

        foreach ($menu as $k1 => $v1)
        {
            $arr2 [$k1] = [];
            if (isset ($v1 ['sub']))
            {
                foreach ($v1 ['sub'] as $k2 => $v2)
                {
                    $tmp2 = [];
                    if (isset ($v2 ['name']))
                    {
                        $tmp2 ['mname'] = $v2 ['name'];
                        $tmp2 ['meid'] = $id++;
                    }
                    else
                    {
                        throw new HttpException('200','菜单配置项有误!');
                    }
                    if (isset ($v2 ['sub']))
                    {
                        $tmp2 ['children'] = [];
                        foreach ($v2 ['sub'] as $k3 => $v3)
                        {
                            $tmp3 = [];
                            if (isset ($v3 ['name']))
                            {
                                $tmp3 ['text'] = $v3 ['name'];
                            }
                            else
                            {
                                throw new HttpException('200','菜单配置项有误!');
                            }
                            if (isset ($v3 ['url']))
                            {
                                $tmp3 ['id'] = $v3 ['url'];
                                if ($isadmin || $this->deep_in_array($tmp3 ['id'], $result))
                                {
                                    if ($user_type == 0)
                                    {
                                        if (isset($v3['development']))
                                        {
                                            if (in_array(APP_STATUS, $v3['development']))
                                            {
                                                array_push($tmp2 ['children'], $tmp3);
                                            }
                                        }
                                        else
                                        {
                                            array_push($tmp2 ['children'], $tmp3);
                                        }
                                    }
                                    elseif ($user_type == 1)
                                    {
                                        if (in_array(1, $v3 ['user_type']))
                                        {
                                            array_push($tmp2 ['children'], $tmp3);
                                        }
                                    }
                                    else
                                    {
                                        if (in_array(2, $v3 ['user_type']))
                                        {
                                            array_push($tmp2 ['children'], $tmp3);
                                        }
                                    }
                                }
                            }
                            else
                            {
                                throw new HttpException('200','菜单配置项有误!');
                            }
                        }
                    }
                    array_push($arr2 [$k1], $tmp2);
                    for ($m = 0; $m < count($arr2 [$k1]); $m++)
                    {
                        if (empty ($arr2 [$k1] [$m] ['children']))
                        {
                            unset ($arr2 [$k1] [$m]);
                        }
                    }
                }
            }
        }

        $one = [];
        foreach ($arr1 as $key => $value)
        {
            foreach ($arr2 [$key] as $uv)
            {

                if (count($uv ['children']))
                {

                    $array ["menu" . $value ['meid']] = $arr2 [$key];
                    $one[$key] = $value;
                }

            }
        }

        $arr = ['one' => $one, 'two' => $array];

        return $arr;
    }

    /**
     * 获取角色列表数据
     * @param $role_type
     * @param string $start
     * @param string $rows
     * @return mixed
     * create by wenQing
     */
    public function getRoleList($role_type,$start = '',$rows = '')
    {
        $temp = Role::where('role_type','=',$role_type);
        if($start) $temp = $temp->offset($start);
        if($rows) $temp = $temp->limit($rows);
        $role = $temp->get()->toArray();
        $count = Role::where('role_type','=',$role_type)->count();

        return [
            'data' => $role,
            'count' => $count
        ];
    }

    /**
     * 获取用户列表
     * @param $user_type
     * @param string $start
     * @param string $rows
     * @return array
     * create by wenQing
     */
    public function getUserList($user_type,$start = '',$rows = '')
    {

        $temp = User::where('type_user','=',$user_type);
        if($start) $temp = $temp->offset($start);
        if($rows) $temp = $temp->limit($rows);
        $role = $temp->get()->toArray();
        $count = User::where('type_user','=',$user_type)->count();

        return [
            'data' => $role,
            'count' => $count
        ];
    }

    /**
     * 获取菜单列表
     * @param $role_id
     * @param int $type_user
     * @return array
     * create by wenQing
     */
    public function getMenuList($role_id, $type_user = 0)
    {
        // 读取该角色拥有权限
        $result = Access::whereIn('role_id',explode(',',$role_id))->get()->toArray();

        if (empty ($result))
        {
            $result = [];
        }
        switch ($type_user)
        {
            case USER_TYPE_PLATFORM :
                $menuData = config('menu.MENU_CRM');;
                break;
            case USER_TYPE_COMMERCIAL :
                $menuData = config('menu.MENU_CRM');;
                break;
            case USER_TYPE_STORE :
                $menuData = config('menu.MENU_CRM');;
                break;
        }
        $menuList = [];
        if ( !empty ($menuData))
        {
            $menuList = $this->createMenuByRecursion($menuData, $result, $type_user);
        }

        return $menuList;
    }

    /**
     * 产生递归菜单
     * @param $menuData
     * @param $access
     * @param $type_user
     * @return array
     * create by wenQing
     */
    public function createMenuByRecursion($menuData, $access, $type_user)
    {
        $menuList = [];
        foreach ($menuData as $k => $v)
        {
            $tmp = [];
            if (isset ($v ['name']))
            {
                $tmp ['text'] = $v ['name'];
            }
            else
            {
                throw new HttpException(200,'菜单配置项有误');
            }
            if (isset ($v ['url']))
            {
                $tmp ['id'] = $v ['url'];
                if ($this->deep_in_array($tmp ['id'], $access))
                {
                    $tmp ['checked'] = true;
                }
            }
            if (isset ($v ['cls']))
            {
                $tmp ['iconCls'] = $v ['cls'];
            }
            if (isset ($v ['sub']))
            {
                $sub = $this->createMenuByRecursion($v ['sub'], $access, $type_user);
                $tmp ['children'] = $sub;
            }
            if (isset ($v ['user_type']))
            {
                if ($type_user == USER_TYPE_PLATFORM)
                {
                    array_push($menuList, $tmp);
                }
                elseif ($type_user == USER_TYPE_COMMERCIAL)
                {
                    if (in_array(USER_TYPE_COMMERCIAL, $v ['user_type']))
                    {
                        array_push($menuList, $tmp);
                    }
                }
                else
                {
                    if (in_array(USER_TYPE_STORE, $v ['user_type']))
                    {
                        array_push($menuList, $tmp);
                    }
                }
            }
            else
            {
                array_push($menuList, $tmp);
            }
        }

        return $menuList;
    }

    public function deep_in_array   ($value, $array)
    {
        foreach($array as $item) {
            if(!is_array($item)) {
                if ($item == $value) {
                    return true;
                } else {
                    continue;
                }
            }

            if(in_array($value, $item)) {
                return true;
            } else if($this->deep_in_array($value, $item)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 添加角色
     */
    public function addRole($roles, $remark, $role_type, $uid)
    {
        $role = new Role();
        $role->role = $roles;
        $role->remark = $remark;
        $role->role_type = $role_type;
        $role->uid_owner = $uid;
        $result = $role->save();

        return $result;
    }

    /**
     * 保存角色的权限
     * @param $role_id
     * @param $access
     * create by wenQing
     */
    public function saveRoleAccess($role_id,$access)
    {
        Access::where('role_id',$role_id)->delete(); //删除原来的权限
        $data = [];
        foreach($access as $k => $v)
        {
            $data[] = [
                'url' => $k,
                'role_id' => $role_id
            ];
        }
        Access::insert($data);
    }

    /**
     * 获取用户角色列表
     * @param $uid
     * @param $role_type
     * @return array|mixed
     * create by wenQing
     */
    public function getRoleUserList($uid, $role_type)
    {
        $isadmin = User::where('uid',$uid)->get()->toArray();
        if ($isadmin [0] ['isadmin'])
        {
            return [];
        }

        $data = $this->getRoleList($role_type);
        $result = $data['data'];
        // 用户当前拥有的角色
        $result2 = RoleUser::where('uid',$uid)->get()->toArray();
        if (empty ($result2))
        {
            return $result;
        }
        foreach ($result as $key => &$value)
        {
            if ($this->deep_in_array($value ['role_id'], $result2))
            {
                $value ['check'] = true;
            }
        }

        return $result;
    }
}