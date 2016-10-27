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
use App\Model\Blog\BlogCategory;
use App\Server\Admin\AuthorityService;
use App\Server\Admin\BlogService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
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

    public function getList(Request $request)
    {
        $this->setDbRead();

        $blogServer = new BlogService();
        $blog = $blogServer->getBlogAndCategory($request->name,$request->page,$request->row);

        return $this->ajaxReturn($blog);
    }

    /**
     * 获取分类
     * create by wenQing
     */
    public function getCategory()
    {
        $this->setDbRead();

        $blogServer = new BlogService();
        $re = $blogServer->getCategory();

        return $this->ajaxReturn($re);
    }

    /**
     * 博客操作
     * @param Request $request
     * @return mixed
     * create by wenQing
     */
    public function option(Request $request)
    {
        $this->setDbRead();
        $blog = new Blog();
        if($request->id)
        {
            //更新操作
            $re = Blog::where('id',$request->id)->update([
                'title' => $request->title,
                'c_id' => $request->category,
                'auth' => $request->auth,
                'body' => $request->body,
            ]);
        }
        else
        {
            $blog->title = $request->title;
            $blog->c_id = $request->category;
            $blog->auth = $request->auth;
            $blog->body = $request->body;
            $blog->create_time = date('Y-m-d H:i:s');
            $re = $blog->save();
        }
        return $this->successReturn('博客操作成功！',$re);
    }
}