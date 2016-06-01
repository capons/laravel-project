<?php

namespace App\Http\Controllers;


use App\model\DB\Category;
use App\model\DB\File;
use App\model\DB\Promise;
use Illuminate\Http\Request;
use Validator;

class PromiseController extends Controller {

	protected $redirectTo = '/dashboard';

	public function validation(Request $request){
		$this->validate($request,
			[
				'title' => 'required|string',
				'desc' => 'required|string',
				'price' => 'required|numeric',
				'terms' => 'required|string',
				'shows' => '',
				'time' => 'after:'.date('Y-m-d',time()),
				//'file' => 'mimes:jpeg,bmp,png',
				//'select_img' => 'array'
			]);
	}

	public function getIndex(){

		return view('promise.index');
	}

	public function add(Request $request){
		$error = [];
		$this->validation($request);
		if(!$request->input('select_img')){
			$v = Validator::make($request->all(), [
				'file' => 'mimes:jpeg,bmp,png',
			]);
			if ($v->fails()){
				return $v->errors();
			}else{
				$file = \Request::file('file');
				$path = \Config::get('app.setting.upload').'\\'.\Auth::user()->id;
				$name = time().'.'.$file->getClientOriginalExtension();
				if( $file->move( $path, $name) ){
					$file = File::create(['name'=>$name,'path'=>$path,'users_id'=>\Auth::user()->id,'url'=> \Config::get('app.setting.url_upload').'/'.\Auth::user()->id]);
				}
			}
		}else{
			$file = File::find($request->input('select_img'));
		}
		$data = $request->all();
		$data['file_id'] = $file->id;
		$promise = Promise::create($data);
		if(!$promise){
			$error = \Lang::get('message.error.save_db');
		}
		return ['error' => $error];
	}

	public function getData(Request $request){
		$value = $request->input('value');
		if($request->input('type') == 1){
			$req = Promise::where('active','=', 1);
			if($value){
				$req = $req->where('category_id','=',$value);
			}
			$req = $req->join('file', 'file_id', '=', 'file.id')->select('file.url','file.name','title','price','desc','promise.id')->get();
			return ['data' => $req];
		}
	}

	public function addRequest(Request $request){
		$error = '';
		$this->validation($request);
		$data = array_merge($request->all(),['type' => 2]);
		$promise = Promise::create($data);
		if(!$promise){
			$error = \Lang::get('message.error.save_db');
		}
		return ['error' => $error];
	}
	//покупка promise
	public function buy(Request $request){
		$msg = ['error' => ''];
		//получение заявки
		$id = \Request::input('id');
		$amount = \Request::input('amount');
		$promise = Promise::find($id);
		//проверка выставляемой цены
		$min = $promise['price'];
		if(!$promise->request->isEmpty()){
			$min += 1;
		}
		$v = Validator::make($request->all(), [
			'amount' => 'required|numeric|min:'.$min,
		]);
		if ($v->fails()) {
			$this->throwValidationException($request, $v);
		}
		//проверка не закончился ли аукцион
		//if($promise['type'] == 1) { //auction
			if($promise['time'] < date('Y-m-d H:i:s')){
				$msg['error'] = \Lang::get('promise.deadline');
				return $msg;
			}
		//}

		$req = [
			'promise_id' => $id,
			'amount' => $amount,
			'users_id' => \Auth::user()->id
		];
		//запись запроса на заяку
		\App\model\DB\Request::create($req);
		//проверка какой вид заявки
		if($promise['type'] == 0){ //buy
			$promise->active = 2;
			$promise->save();
		}else if($promise['type'] == 1){ //auction
			$msg['price'] = $amount;
		}
		return $msg;
	}

	public function check()
	{
		$msg = [];
		$id = \Request::input('id');
		//return ['check' => \App\model\DB\Promise::find($id)->request()->orderBy('amount', 'desc')->first()->users_id];
		$promise = \App\model\DB\Promise::find($id);
		if ($promise->request->isEmpty()) {
			$msg['check'] = false;
		} else {
			if ($promise->request()->orderBy('amount', 'desc')->first()->users_id == \Auth::user()->id) {
				$msg['check'] = true;
			} else {
				$msg['check'] = false;
			}
		}
		return $msg;
	}

	public function getPromiseByCategory(){
		$cat = \Request::input('category');
		$promise = Promise::select('file.name','file.url','promise.*');
		if($cat != 0){
			$promise = $promise->where('category_id',$cat);
		}
		$promise = $promise->join('file','promise.file_id','=','file.id')->get();
		return $promise->toArray();
	}

	public function pageSell(){
		return view('promise.sell');
	}

	public function pageRequest(){
		$category = Category::all();
		return view('promise.request', ['category' => $category]);
	}

	public function pageBuy(){

		return view('promise.buy', [
			'category' => Category::all()
		]);
	}

	public function pageProfile($id){
		$promise = Promise::find($id);
		$req = $promise->request()->orderBy('amount', 'desc')->first();
		return view('promise.profile', ['promise' => $promise, 'request' => $req]);
	}

	public function pageBuypromise(){
		$promise = Promise::where('active',1)->get();
		$cat = Category::all();
		return view('promise.buypromise', ['promise' => $promise,'category' => $cat]);
	}

}