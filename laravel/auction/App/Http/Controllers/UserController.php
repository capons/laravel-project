<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use app\model\DB;
use App\model\DB\File;
use App\User;
use Session; //load session class

class UserController extends Controller {

	protected $redirectTo = 'user/index';

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('guest', ['except' => 'getLogout']);
	}

	public function getIndex(){  //main view

		//var_dump(User::find(4)->toArray());

		return view('user.index'); 
	}

	public function getRegister(){

		return view('user.register');
	}
	public function uploadedFile(){
		$file = File::select()->where('users_id','=',\Auth::user()->id)->get();
		return $file;
	}
}