<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Server\Admin\AuthorityService;
use App\ThirdClass\Verify;
use Mews\Captcha\Facades\Captcha;


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
        $user_info = session('user_info');
        dd($user_info);
        $service = new AuthorityService();
        $result = $service->getUserAccess($user_info ['uid'], $user_info ['isadmin']);

        return $this->ajaxReturn($result);
    }

    /**
     * 登录操作
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * create by wenQing
     */
    public function login()
    {
        return view('admin.login');
    }

    /**
     * 产生验证码图片
     * create by wenQing
     */
    public function img_verify()
    {
        return captcha();
    }
}
