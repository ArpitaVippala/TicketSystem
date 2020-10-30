<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Login;
use DB;
use Auth;
use Session;
use Validator;

class UserController extends Controller
{
    public function login(){
        if(!empty(session('user')['userId'])){
            return redirect()->route('Tickets');
        }else{
            return view('login');
        }
    }

    public function loginUser(Request $req){
        //$user = LoginModel::checkLogin($req);
        if(!empty($req->all())) {            
            $user = Login::checkLogin($req->email, $req->pwd);
            if(!empty($user)){
                // print_r($user);die();
                Session::put('user', ['userId'=>$user[0]->userId, 'userName'=>$user[0]->userName, 'userEmail'=>$user[0]->userEmail]);
                return redirect()->route('Tickets');
            }
        }
    }    
}
