<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AreaModel extends Model
{
    //
    protected $table = 'access';
    protected $primaryKey = 'access_id'; //主键
    public $timestamps = false; //更新时间戳字段
}
