<?php

namespace App\Model\Blog;

use Illuminate\Foundation\Auth\User as Authenticatable;

class BlogCategory extends Authenticatable
{
    protected $table = 'blog_category';
    protected $primaryKey = 'id'; //主键
    public $timestamps = false; //更新时间戳字段

    public function blog()
    {
        return $this->hasMany('App\Model\Blog\Blog','id');
    }
}
