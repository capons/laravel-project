<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;



class AdminController extends Controller {

	private $access = ''; //variable shows user access rights

	public function __construct()
	{
		if(Session::has('user_auth_mess')){
			$user = Session::get('user_auth_mess');
			$this->access = $user[0][4]; //User session variable equate to $access
		}
	}

	public function index(){
		if($this->access == 2) {
			//return view('admin.index',$this->users());
			//$user_data = \DB::table('users')->select('users.f_name','users.email','location.name as location','category.name as category')->join('location','users.location_id','=','location.id')->join('category','users.category_id','=','category.id')->get();
			$user_data = \DB::table('users')->get();
			return view('admin.index',['users' => $user_data]);


		} else {
			Session::flash('user-info', 'Sorry you have no rights'); //send message to user via flash data
			return redirect('/');
		}
	}
	public function pagePromise(){

		if($this->access == 2) {
			return view('admin.promise');
		} else {
			Session::flash('user-info', 'Sorry you have no rights'); //send message to user via flash data
			return redirect('/');
		}
	}
	public function users(){ //method to request by DataTable from view admin/index (and display user data)
		//$user = \DB::table('users')->select('users.f_name','users.email','location.name as location','category.name as category')->join('location','users.location_id','=','location.id')->join('category','users.category_id','=','category.id')->get();
		//return ['data'=>$user];
		$user_data = \DB::table('users')->get();
		return ['data' => $user_data];



	}
	public function promise(){ //display promise data in view -> request from DataTable library from admin/promise view

		$user = \DB::table('promise as p')
			->select('p.title','p.price')
			->get();
		//need to load view for promise controller
		return ['data'=>$user];

	}
}