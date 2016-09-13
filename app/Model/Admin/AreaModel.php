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

    public function getSomeProvinceItemForPage($map = [], $fields = '*',$offset = '', $limit = '', $order = '')
    {
        $temp = DB::table('setting_province')->where();
        if($order)
        {
            $temp = $temp->orderBy($order);
        }
        $return = $this->proModel->getSomeProvinceItemForPage ( $map, $fields, $limit, $order );
        return $return;
    }
}
