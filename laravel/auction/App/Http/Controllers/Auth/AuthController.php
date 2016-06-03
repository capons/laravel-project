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

		return Validator::make($data, [   //validation registration form
			'f_name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|min:6',
			'category_id'=> 'required'
		]);

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
	 */
	public function postLogin(Request $request) //login via email + pass or name + pass
	{

		//$this->validate($request, [
		//	$this->loginUsername() => 'required', 'password' => 'required',
		//]);

		// If the class is using the ThrottlesLogins trait, we can automatically throttle
		// the login attempts for this application. We'll key this by the username and
		// the IP address of the client making these requests into this application.

		$throttles = $this->isUsingThrottlesLoginsTrait();

		if ($throttles && $this->hasTooManyLoginAttempts($request)) {
			return $this->sendLockoutResponse($request);
		}

		//$credentials = $this->getCredentials($request);
		$userdata_email = array( //login via email
			'email'     => Input::get('r_email'),
			'password'  => Input::get('r_password')
		);
		$userdata_name = array( //login via name
			'f_name'    => Input::get('r_email'),
			'password'  => Input::get('r_password')
		);
		$rules = array(                 //validation rules
			'r_email'    => 'required', // make sure the email is an actual email
			'r_password' => 'required' // password can only be alphanumeric and has to be greater than 3 characters
		);
		$messages = [ //validation message
			'required' => 'The :attribute field is required.',
		];
		$validator = Validator::make(Input::all(), $rules,$messages);
		if ($validator->fails()) { //if true display error
			return redirect('auth/login')
				->withInput()
				->withErrors($validator); //set validation error name to display in error layout  views/common/error.blade.php
		} else {
			if (Auth::attempt(/*$credentials*/$userdata_email + ['active' => 1], $request->has('remember'))) { //avtive need to be 1 to check if user active account
				Session::flash('user-info', 'You have successfully sign in'); //send message to user via flash data
				$data = array(  //push user data into array
					Auth::user()->id,
					Auth::user()->f_name,
					Auth::user()->l_name,
					Auth::user()->email,
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
		return redirect($this->loginPath())
			->withInput($request->only($this->loginUsername(), 'remember'))
			->withErrors([
				$this->loginUsername() => $this->getFailedLoginMessage(),
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
		Auth::login($this->create($request->all()));                    //save data to database
		Session::flash('user-info', 'You have successfully signed up'); //send message to user via flash data
		return redirect($this->redirectPath());                         //redirect controller set in protected $redirectTo = '/';
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
		return User::create([
			'f_name' => $data['f_name'],
			'l_name' => $data['l_name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'location_id' => $data['location_id'],
			'category_id' => $data['category_id'],
			'active' => 1 //set user to active (need to be confirm on email address in future)
		]);
	}
}
