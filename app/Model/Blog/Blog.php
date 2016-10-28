<?php

namespace App\Model\Blog;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blog';
    protected $primaryKey = 'id'; //主键
    public $timestamps = false; //更新时间戳字段

    /**
     * 添加一个分类信息
     * @return mixed
     * create by wenQing
     */
    public function getCategoryNameAttribute()
    {
        return $this->category->name;
    }
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
