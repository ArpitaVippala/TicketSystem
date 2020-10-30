<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Employee extends Model
{
	protected $table = 'employee';
	
    public static function getdata(){
    	$res = DB::table('employee as e')
    			// ->from("employee e")
    			->join("employee_salary as es", "e.emp_id", '=', "es.emp_id")
    			->select("e.*", "es.*")
    			// ->where('e.emp_id', '1')
    			->orderby('e.emp_id', 'asc')
    			->limit('2')
    			->get();
    	return $res;
    }
}
