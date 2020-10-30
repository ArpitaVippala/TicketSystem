<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserLogin extends Model
{
    public static function checkLogin($email, $pwd){
        // echo $email;
        $res = DB::table('login')
                ->where('userEmail', $email)->value('userId');
                // $res11 = DB::table('login')->toSql();
                print_r($res);die();
                // die();
                return $res;
        // if(!empty($res)){
        //     return $res->userId;
        // }else{
        //     return 0;
        // }
    }
}
