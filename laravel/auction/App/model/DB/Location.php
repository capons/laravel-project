<?php

namespace App\model\DB;


use Illuminate\Database\Eloquent\Model;

class Location extends Model {

	public $timestamps = false;
	public $table = 'location';

	public static function getSelect(){
		$instance = new static;
		$select = '<select id="location" name="location_id" class="input_form">';
		foreach($instance->all()->toArray() as $v) {
			$select .= "<option value=\"$v[id]\">$v[name]</option>";
		}
		$select .= '</select>';
		echo $select;
	}
}