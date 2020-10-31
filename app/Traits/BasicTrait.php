<?php

namespace App\Traits;
use DB;

trait BasicTrait
{
    public function curlUrl($url, $data, $type){
        // $res = DB::select("select userid, concat(firstname, ' ', lastname) as fullname from users where usertype != 'Admin' ");
        // return $res;
        $headers=array(
            "Authorization: 9446933330c7f886fbdf16782906a9e0",
            "orgId: 60001280952",
            "contentType: application/json",
        );
        $ch = curl_init("https://desk.zoho.in/api/v1/$url");
        if($type == 1){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        }
        
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $content = curl_exec($ch);
        $info= curl_getinfo($ch);
    
    /*if($info['http_code']==200){
        echo "<h2>Request Successful, Response:</h2> <br>";
        echo $content;
    }
    else{
        echo "Request not successful. Response code : ".$info['http_code']." <br>";
        echo "Response : $content";
    }die();*/
        curl_close($ch);
        return $content;
    }
}
