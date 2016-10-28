<?php
/**
 * Created by PhpStorm.
 * User: wenQing
 * Date: 2016/10/25
 * Time: 10:23
 */
namespace App\Server\Admin;

use App\Model\Blog\Blog;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class BlogService
{
    /**
     * 获取博客和分类的信息 默认每页10个
     * @param $field
     * @param $status
     * @param $page
     * @param $rows
     * @return array
     * create by wenQing
     */
    public function getBlogAndCategory($status = '',$page = 1,$rows = 10)
    {
        $start = ($page-1)*$rows;

        $count = DB::table('blog as b')->leftJoin('blog_category as c','c.id','=','b.c_id')->count(); //全部数据

        $db = DB::table('blog as b');
        if(!empty($status)) $db = $db->where('status',$status);
        if(!empty($page) && !empty($rows)) $db = $db->limit($start,$rows);
        $re = $db
            ->select('b.*','c.name')
            ->leftJoin('blog_category as c','c.id','=','b.c_id')
            ->get();

        return [
            'rows' => $re,
            'total' => $count
        ];
    }

    /**
     * 获取博客数据
     * @param int $page
     * @return mixed
     * create by wenQing
     */
    public function getBlogAndCategoryForHome($page = 1)
    {
        $rows = Config::get('common.rows');
        $start = ($page-1)*$rows;
        $result = Blog::where('status',1)->take($rows)->skip($start)->get();

        return $result->toJson();
    }

    public function getCategory()
    {
        $re = DB::table('blog_category')->select('id','name')->get();
        return $re;
    }
}