<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Session;
use Cache;

class AdminController extends Controller{
    
    public function newTicket(){
        if(!empty(session('user')['userId'])){        
            $departments = DB::table('departments')->get();
            $categories = DB::table('categories')->get();
            return view('admin.createTicket', array('depts'=>$departments, 'cats'=>$categories));
        }else{
            return redirect()->route('Login');
        }
    }

    public function saveTicket(Request $req){
        if(!empty(session('user')['userId'])){
            if(!empty($req->all())){
                // print_r($req->all());
                $departments = DB::table('departments')->get();
                $categories = DB::table('categories')->get();
                return redirect()->route('Create')->with(array('depts'=>$departments, 'cats'=>$categories, 'msg'=>'Ticket created successfully'));
            }
        }else{
            return redirect()->route('Login');
        }
    }

    public function alltickets(){
        if(!empty(session('user')['userId'])){
            return view('admin.dashboard');
        }else{
            return redirect()->route('Login');
        }
    }

    public function logout(){
        Auth::logout();
        Session::flush();
        cache::flush();
        return redirect()->route('Login');
    }
}
