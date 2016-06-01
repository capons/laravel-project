<?php

namespace App\model\DB;


use Illuminate\Database\Eloquent\Model;

class Request extends Model {

	public $timestamps = false;
	public $table = 'request';
	protected $fillable = array('promise_id','users_id','amount');

	function category(){
		$this->belongsTo('App\model\DB\Promise');
	}
	function user(){
		$this->belongsTo('App\User');
	}
}