<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Model\Admin\User;
use App\Server\Admin\AuthorityService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;


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
        $s = Cookie::get('platform_login_info');
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
    public function loginCheck(Request $request)
    {
        $input = Input::all();
        $rules = [
            'user' => 'required',
            'pass' => 'required'
        ];
        $message = [
            'user.required' => '用户名不能为空',
            'pass.required' => '密码不能为空',
        ];
        //添加一个测试的验证码，方便开发使用的
        if($input['vcode'] != '1111')
        {
            $rules['vcode'] = 'required|captcha';
            $message['vcode.required'] = '验证码不能为空';
            $message['vcode.captcha'] = '验证码不正确';
        }
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails())
        {
            return $this->errorReturn($validator->errors()->all());
        }

        try
        {
            // 验证用户密码
            $service = new AuthorityService ();
            $auto = array_key_exists('auto',$input) ? $input['auto'] : '0';
            $result = $service->checkUserPass($input['user'], $input['pass'], $auto);
            $request->session()->put('user_info', $result);//用户信息保存到session
            //$request->session()->get('user_info');
            $access = $service->getAllAccessByUid($result ['uid']);
            $request->session()->put('user_access', $access);//用户权限信息保存到session
        }
        catch(HttpException $e)
        {
            return $this->errorReturn($e->getMessage());
        }

        return $this->successReturn('登录成功!');
    }
}
