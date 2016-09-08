<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Server\Admin\AuthorityService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Support\Facades\Input;
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

    /**
     * 登录验证
     * create by wenQing
     * @throws AuthorizationException
     */
    public function loginCheck()
    {
        $rules = [
            'vcode' => 'required|captcha',
            'user' => 'required',
            'pass' => 'required'
        ];
        $message = [
            'vcode.required' => '验证码不能为空',
            'vcode.captcha' => '验证码不正确',
            'user.required' => '用户名不能为空',
            'pass.required' => '密码不能为空',
        ];
        $validator = Validator::make(Input::all(), $rules, $message);
        if ($validator->fails())
        {
            return $this->errorReturn($validator->errors()->all());
        }
        return $this->successReturn('登录成功!');
    }
}
