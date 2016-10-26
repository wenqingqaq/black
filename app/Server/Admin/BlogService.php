<?php
/**
 * Created by PhpStorm.
 * User: wenQing
 * Date: 2016/10/25
 * Time: 10:23
 */
namespace App\Server\Admin;

use Illuminate\Support\Facades\DB;

class BlogService
{
    /**
     * 获取博客和分类的信息
     * @param $field
     * @param $where
     * @param $page
     * @param $rows
     * @return array
     * create by wenQing
     */
    public function getBlogAndCategory($where,$page,$rows)
    {
        $start = ($page-1)*$rows;

        $count = DB::table('blog as b')->leftJoin('blog_category as c','c.id','=','b.c_id')->count(); //全部数据

        $db = DB::table('blog as b');
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

    public function getCategory()
    {
        $re = DB::table('blog_category')->select('id','name')->get();
        return $re;
    }
}