<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\BasicTrait;
use DB;
use Auth;
use Session;
use Cache;

class AdminController extends Controller{
    
    use BasicTrait;
    public function newTicket(){
        if(!empty(session('user')['userId'])){        
            // $departments = DB::table('departments')->get();
            $categories = DB::table('categories')->get();
            $dept = $this->curlUrl('departments', [], 0);
            $dept = json_decode($dept);
            return view('admin.createTicket', array('depts'=>$dept->data, 'cats'=>$categories));
        }else{
            return redirect()->route('Login');
        }
    }

    public function saveTicket(Request $req){
        if(!empty(session('user')['userId'])){
            if(!empty($req->all())){
                $requestObj=[];
                $requestObj['departmentId']=$req->department;
                $contact=[];
                $contact['email']=$req->email;
                $contact['firstName']=$req->name;
                // $contact['lastName']='ticket';
                $requestObj['contact']=$contact;
                $requestObj['subject']=$req->subject;
                $requestObj['category']=$req->category;
                $requestObj['description']=$req->description;
                $requestObj['priority']=$req->priority;
                $response=$this->curlUrl('tickets',json_encode($requestObj), 1);
                $categories = DB::table('categories')->get();
                $dept = $this->curlUrl('departments', [], 0);
                $dept = json_decode($dept);
                if(!empty($response)){
                    $response = json_decode($response);
                    // print_r($response);die();
                    // if(session('user')['contactId']){
                        $res = DB::table('login')->where('userEmail', $req->email)->update(array('contactId'=>$response->contactId));
                    // }
                    return redirect()->route('Create')->with(array('depts'=>$dept->data, 'cats'=>$categories, 'msg'=>'Ticket created successfully'));
                }else{
                    return redirect()->route('Create')->with(array('depts'=>$dept->data, 'cats'=>$categories, 'error'=>'Oops! Something went wrong.'));
                }                
            }
        }else{
            return redirect()->route('Login');
        }
    }

    public function alltickets(){
        if(!empty(session('user')['userId'])){
            
            $contactId = DB::table('login')->where('userId', session('user')['userId'])->value('contactId');
            if($contactId == ''){
                return view('admin.dashboard', array('tickets'=>[]));
            }else{
                $url = "contacts/".$contactId."/tickets";
                $dataa = $this->curlUrl($url, [], 0);
                if(!empty($dataa)){
                    // print_r($dataa);die();
                    $ticketData = json_decode($dataa);
                    return view('admin.dashboard', array('tickets'=>$ticketData->data));
                }   
            }                     
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
