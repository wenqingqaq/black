<?php

namespace App\Model\Blog;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Blog extends Authenticatable
{
    protected $table = 'blog';
    protected $primaryKey = 'id'; //主键
    public $timestamps = false; //更新时间戳字段

    /**
     * 获取分类信息
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * create by wenQing
     */
    public function category()
    {
        return $this->belongsTo('App\Model\Blog\BlogCategory','c_id');
    }
}
