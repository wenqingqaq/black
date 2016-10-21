<?php
/**
 * 后台博客管理
 * Created by PhpStorm.
 * User: wenQing
 * Date: 2016/10/21
 * Time: 15:35
 */
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Model\Admin\User;
use App\Model\Blog\Blog;
use App\Server\Admin\AuthorityService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;


class BlogController extends CommonController
{
    /**
     * 博客显示界面
     * create by wenQing
     */
    public function index()
    {
        return view('admin.blog');
    }

    public function getList()
    {
        $cate = Blog::find(1)->category;
        dd($cate);
    }
}