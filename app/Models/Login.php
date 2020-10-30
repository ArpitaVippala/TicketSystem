<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Login extends Model
{
    // protected $table = 'login';
    
    public static function checkLogin($email, $pwd){
        // echo $email;
        $res = DB::table('login')
            ->select('*')
            ->where('userEmail', $email)->get();
            return $res;
        if(!empty($res)){
            return $res;
        }else{
            return '';
        }
    }
}
