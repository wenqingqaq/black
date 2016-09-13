<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Access extends Model
{
    protected $table = 'access';
    protected $primaryKey = 'access_id'; //主键
    public $timestamps = false; //更新时间戳字段
    /**
     * 获取权限通过用户id
     * @param $uid
     * @return mixed
     * create by wenQing
     */
    public function selectAccessByUid($uid)
    {
        return RoleUser::where('role_user.uid','=',$uid)
            ->select('a.url')
            ->join('access as a','role_user.uid','=','a.role_id')
            ->groupBy('a.url')
            ->get()->toArray();
//        return DB::table('role_user as ru')
//            ->join('access as a','ru.uid','=','a.role_id')
//            ->groupBy('a.url')
//            ->where('ru.uid','=',$uid)->get(['a.url'])->toArray();
    }
}
