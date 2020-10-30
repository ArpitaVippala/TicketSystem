<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Models\User; 
use App\Models\Users; 
use App\Traits\BasicTrait;
use Auth; 
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Session;
use Redirect;
use Config;
use Cache;

class UserController extends Controller
{
    use BasicTrait;
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 

    public function login(Request $request){ 
        $res = DB::select("exec sp_validatelogin '$request->email', '$request->password', '', '', ''");
        if(!empty($res)){
            // print_r($res);die();
            // echo $res[0]->userid; die();
            if($res[0]->status == 'VALID'){
                $userid = $res[0]->userid;
                $userDetails = DB::select("exec sp_getuserdetails $userid,null,null,null,null");
                if(!empty($userDetails)){
                    Session::put('user', ['username' => $userDetails[0]->firstName.' '.$userDetails[0]->lastName, 'userId' => $res[0]->userid, 'role' => $userDetails[0]->usertype, 'today' => $userDetails[0]->currdate]);
                }                
                if((Session::get('user')['role'] == 'User') || (Session::get('user')['role'] == 'Manager')){
                    return redirect()->route('clockin');
                }else{
                    return redirect()->route('dashboard');
                }                
            }else{
                return redirect()->back()->with('error', $res[0]->status);
            }
        }else{
            return redirect()->back()->with('error', "Invalid Login ID and Password Combination. Please check");
        }
    }
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
		if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }else{
        	$input = $request->all(); 
	        $input['password'] = bcrypt($input['password']); 
	        // $user = User::create($input); 
	        $user = User::create([
	            'name' => $input['name'],
	            'email' => $input['email'],
	            'password' => Hash::make($input['password']),
	        ]);
	        $success['token'] =  $user->createToken('MyApp')->accessToken; 
	        $success['name'] =  $user->name;
		    return response()->json(['success'=>$success], $this->successStatus);
        }
    }
    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this->successStatus); 
    } 

    public function signin(){
    	return view('register');
    }

    public function userLogin(){
        return view('login');
    }

    public function fetchCurrTime(){
        $res = DB::select("exec sp_givecurrenttime null");
        return $res[0]->currtime;        
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        Cache::flush();
        return redirect()->route('clockin');
    }

    public function changePwd(){
        return view('changePassword');
    }

    public function saveNewPwd(Request $request){
        $msg = "";
        if(!empty($request)){
            $userid = session('user')['userId'];
            $userData = $this->getUserDetails($userid);
            if(!empty($userData)){
                /*echo '<pre>';
                print_r($userData);
                die();*/
                if(strcmp($userData[0]->passwd, $request->oldPwd) == 0){
                    $data = array('passwd'=>$request->newPwd);
                    $res = DB::table('users')->where('userid', $userid)->update($data);
                    $msg = "Your password updated successfully";
                    return redirect()->route('changePassword')->with(array('success'=>"Your password updated successfully"));
                }else{
                    return redirect()->route('changePassword')->with(array('error'=>"Oops! Please enter correct old password"));
                }
            }else{
                return redirect()->route('changePassword')->with(array('error'=>"Oops! Something went wrong. Please try again"));
            }
        }else{
            return redirect()->route('changePassword')->with(array('error'=>"Please enter data"));
        }
        
    }

    public function forgotPassword(){
        return view('forgotPwd');
    }

    public function forgotPwdNew(){
        return view('forgotPwdNew');
    }

    public function sendPwdLink(Request $request){
        $input = $request->all();
        if(!empty($input)){
            $email = base64_encode($input['email']);
            $url = url('/pwdMail').'/'.$email.'/'.time();
            $link = '<a href="'.$url.'">Click Here</a>';
            MailController::sendForgotPwdMail($link, $email);
            return redirect()->route('forgotPwd')->with(array('success'=>'Email sent.. Check your Inbox/Spam'));
        }
    }

    //check email link expiration
    public function pwdMail(Request $request){
        $currTime = time();
        /*echo $currTime;
        echo "=========";
        echo $currTime - $request->time;die();*/
        if(($currTime - $request->time) < 1800){
            $expired = '0';
        }else{
            $expired = '1';
        }
        return view('forgotPwdNew', array('email'=>base64_decode($request->email), 'expiry'=>$expired));
    }

    public function saveForgotPwd(Request $request){
        if(!empty($request)){
            $data = array('passwd'=>$request->password);
            $res = DB::table('users')->where('email', $request->email)->update($data);
            return redirect()->route('login')->with(array('success'=>'Your password is set. Please login with new password'));
        }
    }

    /*
    public function testConn(Request $request){
        $conn = sqlsrv_connect(Config::get('constants.customDB.serverName'), Config::get('constants.customDB.connectionOptions'));
            if($conn){
                $companyArr = $companyUsersArr = array();
                $res11 = sqlsrv_query($conn, "exec sp_validatelogin ?,?,?,?,?", array($request->email, $request->password,'','',''));
                $res = sqlsrv_fetch_array($res11, SQLSRV_FETCH_ASSOC);
                // print_r($res);die();
                if($res['status'] == 'VALID'){
                    // $request->session()->put('username', $row[1].' '.$row[2]);
                    Session::put('username', $res['firstname'].' '.$res['lastname']);
                    /*$companyList = sqlsrv_query($conn, "exec sp_getcompany ?,?,?", array('','',''));
                    //print_r($companyList);die();
                    while($ress = sqlsrv_fetch_array($companyList, SQLSRV_FETCH_NUMERIC)){
                        $companyArr[$ress[0]] = $ress[2];
                    }

                    $companyUsersList = sqlsrv_query($conn, "exec sp_getuserslist ?,?,?,?,?", array('','','','',''));
                    //print_r($companyList);
                    while($ress1 = sqlsrv_fetch_array($companyUsersList, SQLSRV_FETCH_NUMERIC)){
                        $companyUsersArr[$ress1[0]] = $ress1[2];

                    }

                    dd($companyArr);
                    dd($companyUsersArr);*
                    // return view('admin/profile', array('companyList' => $companyArr, 'companyUsers' => $companyUsersArr));
                    // return Redirect::route('profile', array('companyList' => $companyArr, 'companyUsers' => $companyUsersArr));
                    return redirect()->route('processingSummary');
                }else{
                    return redirect()->back()->with('message', $res['status']);
                }
            }else{
                die("Could not find the database. Please check your configuration.");
            }
        //try {
            /*$username = Session::get('username');
            $data = Users::isAdmin($username);
            print_r($data);*
            if(DB::connection()->getDatabaseName()){
                echo "Yes! Successfully connected to the DB: " . DB::connection()->getDatabaseName();
            }else{
                // connect manually
                $serverName = "40.70.213.134";
                $connectionOptions = array(
                    "Database" => "myappdb",
                    "Uid" => "myapp",
                    "PWD" => "BlueThunder2019@"
                );
                //Establishes the connection
                $conn = sqlsrv_connect($serverName, $connectionOptions);
                if($conn){
                    $res11 = sqlsrv_query($conn, "exec sp_validatelogin ?,?,?,?,?", array($request->email, $request->password,'','',''));
                    $row = sqlsrv_fetch_array($res11, SQLSRV_FETCH_NUMERIC);
                    // print_r($row);
                    if(in_array('VALID', $row)){
                        // $request->session()->put('username', $row[1].' '.$row[2]);
                        Session::put('username', $row[0].' '.$row[1]);
                        $companyList = sqlsrv_query($conn, "exec sp_getcompany ?,?,?", array('','',''));
                        // print_r($companyList);
                        while($ress = sqlsrv_fetch_array($companyList, SQLSRV_FETCH_NUMERIC)){
                            $companyArr[$ress[0]] = $ress[2];
                        }

                        $companyUsersList = sqlsrv_query($conn, "exec sp_getuserslist ?,?,?,?,?", array('','','','',''));
                        // print_r($companyList);
                        while($ress1 = sqlsrv_fetch_array($companyUsersList, SQLSRV_FETCH_NUMERIC)){
                            $companyUsersArr[$ress1[0]] = $ress1[2];

                        }

                        // dd($companyArr);
                        // dd($companyUsersArr);
                        return view('admin/profile', array('companyList' => $companyArr, 'companyUsers' => $companyUsersArr));
                        // return Redirect::route('profile', array('companyList' => $companyArr, 'companyUsers' => $companyUsersArr));
                    }else{
                        return redirect()->back()->with('message', $row[2]);
                    }
                }else{
                    die("Could not find the database. Please check your configuration.");
                }
                
            }
        /*} catch (\Exception $e) {
            die("Could not connect to the database.  Please check your configuration. error:" . $e );
        }*
    }*/
}
