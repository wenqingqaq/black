<?php

namespace App\Model\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';
    protected $primaryKey = 'uid'; //主键
    public $timestamps = true; //更新时间戳字段
}
