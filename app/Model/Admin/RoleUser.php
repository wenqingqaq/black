<?php
/**
 * 用户和角色关联的表
 */
namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    //
    protected $table = 'role_user';
    protected $primaryKey = 'uid'; //主键
    public $timestamps = false; //更新时间戳字段
}
