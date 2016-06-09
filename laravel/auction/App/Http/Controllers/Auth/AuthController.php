<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers, ThrottlesLogins;

	//protected $redirectTo = 'user/index'; //было сначало
	protected $redirectTo = '/'; //redirect path after sign in
	private $last_id = '';  //put into variable last insert id
	private $hash = ''; //put into variable user hash to confirm user account
	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('guest', ['except' => 'getLogout']); //было вначале // redirect from Middleware/RedirectifAuth
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */

	protected function validator(array $data)
	{
		$messages = [ //validation message
			'f_name.required' => 'Name is required',
			'email.required' => 'Email is required',
			'password.required' => 'Password is required',
			'category_id.required' => 'Category is required',
			'terms.required' => 'Terms and Condition is required',
		];
		return Validator::make($data, [   //validation registration form
			'f_name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|min:6',
			'category_id'=> 'required',
			'terms' => 'required'
		],$messages);

	}


	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
	 */
	public function postLogin(Request $request) //login via email + pass or name + pass
	{
		// If the class is using the ThrottlesLogins trait, we can automatically throttle
		// the login attempts for this application. We'll key this by the username and
		// the IP address of the client making these requests into this application.

		$throttles = $this->isUsingThrottlesLoginsTrait();

		if ($throttles && $this->hasTooManyLoginAttempts($request)) {
			return $this->sendLockoutResponse($request);
		}

		//$credentials = $this->getCredentials($request);
		$userdata_email = array( //login via email
			'email'     => Input::get('r_email'),  //email -> database row name
			'password'  => Input::get('r_password')//password -> database row name
		);
		$userdata_name = array( //login via name
			'f_name'    => Input::get('r_email'),
			'password'  => Input::get('r_password')
		);
		$messages = [ //validation message
			'required' => 'The :attribute field is required.',
		];
		//$validator = Validator::make(Input::all(), $rules,$messages);
		$validator = Validator::make($request->all(), [
			'r_email' => 'required',
			'r_password' => 'required'
		], $messages);
		if ($validator->fails()) { //if true display error
			return redirect('auth/login')
				->withInput()
				->withErrors($validator); //set validation error name to display in error layout  views/common/errors.blade.php
		} else {
			if (Auth::attempt(/*$credentials*/$userdata_email + ['active' => 1], $request->has('remember'))) { //avtive need to be 1 to check if user active account
				Session::flash('user-info', 'You have successfully sign in'); //send message to user via flash data
				$data = array(  //push user data into array
					Auth::user()->id,
					Auth::user()->f_name,
					Auth::user()->l_name,
					Auth::user()->email,
					Auth::user()->access
				);
				if (Session::has('user_auth_mess')) { //if session isset redirect if no push data to session
					return $this->handleUserWasAuthenticated($request, $throttles);
				} else {
					Session::push('user_auth_mess', $data);  //$data is an array and user is a session key.
					return $this->handleUserWasAuthenticated($request, $throttles);
				}
			} elseif (Auth::attempt(/*$credentials*/$userdata_name + ['active' => 1], $request->has('remember'))) {
				Session::flash('user-info', 'You have successfully sign in'); //send message to user via flash data
				$data = array(  //push user data into array
					Auth::user()->id,
					Auth::user()->f_name,
					Auth::user()->l_name,
					Auth::user()->email,
					Auth::user()->access
				);
				if (Session::has('user_auth_mess')) { //if session isset redirect if no push data to session
					return $this->handleUserWasAuthenticated($request, $throttles);
				} else {
					Session::push('user_auth_mess', $data);  //$data is an array and user is a session key.
					return $this->handleUserWasAuthenticated($request, $throttles);
				}
			}
		}

		// If the login attempt was unsuccessful we will increment the number of attempts
		// to login and redirect the user back to the login form. Of course, when this
		// user surpasses their maximum number of attempts they will get locked out.

		if ($throttles) {
			$this->incrementLoginAttempts($request);
		}

		//return redirect($this->loginPath())
		return redirect('auth/login') //redirect to with message
			->withInput($request->only($this->loginUsername(), 'remember'))
			->withErrors([
				//$this->loginUsername() => $this->getFailedLoginMessage(),
				'Invalid username or password',
			]);

	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function postRegister(Request $request) //save registration user data
	{
		$validator = $this->validator($request->all());
		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}
		Auth::login($this->create($request->all()));
		//$user = Auth::login($this->create($request->all()));                    //save data to database
		//$insertedId = $this->last_id;
		//send email to confirm registration to admin email -> Далее админ переходит по ссылке и активирует пользователя -> После активации пользователя пользователь получает email о том что его аккаунт активирован

		//Вернуть last insert id
		

		$user = User::findOrFail($this->last_id);
		Mail::send('mail.index', ['user' => $user], function ($m) use ($user) {
			$m->from('hello@app.com', 'Your Application');

			$m->to('adminemail@gmail.com', $user->name)->subject(Config::get('app.url').'/auth/active' . '?hash=' . $this->hash . '&id=' . $this->last_id . ''); //send to email link to activate account
		});



		Session::flash('user-info', 'Your registration has been successfully submitted
									for approval and you will be notified via email when live.'); //send message to user via flash data
		//return redirect($this->redirectPath());                         //redirect controller set in protected $redirectTo = '/';
		return redirect('auth/register');
	}

	public function postActivate(Request $request) //activate user account
	{
		/*
		$validator = $this->validator($request->all());
		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}
		Auth::login($this->create($request->all()));
		*/
		//$user = Auth::login($this->create($request->all()));                    //save data to database
		//$insertedId = $this->last_id;
		//send email to confirm registration to admin email -> Далее админ переходит по ссылке и активирует пользователя -> После активации пользователя пользователь получает email о том что его аккаунт активирован

		Validator::make($request->all(), [
			'id' => 'integer'
		]);

		$hash = Input::get('hash'); //user data id
		$id = Input::get('id');

		//$find_user = User::where('id',$id)->where('hash',$hash)->get();
		//$find_user = User::where('id',$id)->where('hash',$hash)->get(); //find user with correct id and hash
		$find_user = User::where('id', $id)
			//->where('hash',$hash)
			->get();
		if($find_user){
			$values=array('active'=>1,'hash'=>'new hash'); //update data -> new hash to confirm that we active user acount
			User::where('id',$id)->where('hash',$hash)->update($values);
			//foreach ($find_user as $user) {
				//Session::flash('user-info', $user->id);
				Mail::send('mail.index', ['user' => $find_user], function ($m) use ($find_user) {
					$m->from('hello@app.com', 'Your Application');

					$m->to('bogffff@ram.ru', 'имя отправителя')->subject('Congratulations your account is activated'); //send to user email info that we activate user account
				});
			//}
			//send mail to
			return redirect('auth/register');
		} else {
			Session::flash('user-info', 'Invalid link');
			return redirect('auth/register');
		}
	}

	/**
	 * @return mixed
	 */
	public function getLogout() //logout user
	{
		Session::flush();
		return redirect('/');
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 * @return User
	 */

	protected function create(array $data){ //method to save registration user data to database
		/*
		return User::create([
			'f_name' => $data['f_name'],
			'l_name' => $data['l_name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'location_id' => $data['location_id'],
			'category_id' => $data['category_id'],
			'hash' => bcrypt($data['f_name'])
			//'active' => 1 //set user to active (need to be confirm on email address in future)
		]);
		*/
		$this->hash = bcrypt($data['f_name']); //put user account activate hash into variable
		$save_data = User::create([
			'f_name' => $data['f_name'],
			'l_name' => $data['l_name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'location_id' => $data['location_id'],
			'category_id' => $data['category_id'],
			'hash' => $this->hash
			//'active' => 1 //set user to active (need to be confirm on email address in future)
		]);
		$this->last_id = $save_data->id;

		return $save_data;
	}
}
