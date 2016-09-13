<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Access extends Model
{
    //

    public function selectAccessByUid($uid)
    {
        DB::select('SELECT b.url FROM role_user a JOIN access b ON a.role_id = b.role_id WHERE uid = ? GROUP BY b.url');
    }
}
