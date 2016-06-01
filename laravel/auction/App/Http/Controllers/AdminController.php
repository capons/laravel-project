<?php

namespace App\Http\Controllers;


class AdminController extends Controller {

	public function index(){

		return view('admin.index');
	}
	public function pagePromise(){

		return view('admin.promise');
	}
	public function users(){
		$user = \DB::table('users')->select('users.f_name','users.email','location.name as location','category.name as category')->join('location','users.location_id','=','location.id')->join('category','users.category_id','=','category.id')->get();
		return ['data'=>$user];
	}
	public function promise(){

		$user = \DB::table('promise as p')
			->select('p.title','p.price')
			->get();
		//need to load view for promise controller
		return ['data'=>$user];

	}
}