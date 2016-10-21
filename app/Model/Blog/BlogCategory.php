<?php

namespace App\Model\Blog;

use Illuminate\Foundation\Auth\User as Authenticatable;

class BlogCategory extends Authenticatable
{
    protected $table = 'blog_category';
    protected $primaryKey = 'id'; //主键
    public $timestamps = false; //更新时间戳字段

}
