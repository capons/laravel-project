<?php

namespace App\model\DB;


use Illuminate\Database\Eloquent\Model;

class Category extends Model {

	protected $table = 'category';
	public $timestamps = false;

	public static function getSelect(){
		$instance = new static;
		$select = '<select id="cat" name="category_id" class="input_form">';
		foreach($instance->all()->toArray() as $v) {
			$select .= "<option value=\"$v[id]\">$v[name]</option>";
		}
		$select .= '</select>';
		echo $select;
	}
}