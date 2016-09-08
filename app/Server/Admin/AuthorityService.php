<?php
/**
 * Created by PhpStorm.
 * User: wenQing
 * Date: 2016/9/8
 * Time: 10:39
 */
namespace App\Server\Admin;

use App\Model\RoleUser;

class AuthorityService
{
    public function getUserAccess($uid, $isadmin, $user_type = 0)
    {
        if ( !$isadmin)
        {
            $roleUserModel = new RoleUser();
            $roleId = $roleUserModel->selectRoleUser("uid = {$uid}");
            if ($roleId != null)
            {
                $n = '';
                for ($i = 0; $i < count($roleId); $i++)
                {
                    $n .= $roleId [$i] ['role_id'] . ',';
                }
                $n = substr($n, 0, -1);
                $accessModel = D('User/Access');
                $result = $accessModel->selectAccess("role_id IN ({$n})");
            }
            else
            {
                return false;
            }
        }
        switch ($user_type)
        {
            case 0 :
                $menu = C('MENU_CRM');
                break;
            case 1 :
                $menu = C('MENU_CRM');
                break;
            case 2 :
                $menu = C('MENU_CRM');
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
                        throwErrMsg("菜单配置项有误");
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
                                throwErrMsg("菜单配置项有误");
                            }
                            if (isset ($v3 ['url']))
                            {
                                $tmp3 ['id'] = $v3 ['url'];
                                if ($isadmin || deep_in_array($tmp3 ['id'], $result))
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
                                throwErrMsg("菜单配置项有误");
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
}