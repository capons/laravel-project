<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Lang;

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
	protected $redirectTo = '/';

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

	public function postLogin(Request $request)
	{

		$this->validate($request, [
			$this->loginUsername() => 'required', 'password' => 'required',
		]);

		// If the class is using the ThrottlesLogins trait, we can automatically throttle
		// the login attempts for this application. We'll key this by the username and
		// the IP address of the client making these requests into this application.

		$throttles = $this->isUsingThrottlesLoginsTrait();

		if ($throttles && $this->hasTooManyLoginAttempts($request)) {
			return $this->sendLockoutResponse($request);
		}

		$credentials = $this->getCredentials($request);

		if (Auth::attempt($credentials  + ['active' => 0], $request->has('remember'))) { //avtive need to be 1 to check if user active account
			return $this->handleUserWasAuthenticated($request, $throttles);
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

	public function postRegister(Request $request) //save registration user data
	{
		$validator = $this->validator($request->all());

		if ($validator->fails()) {
			$this->throwValidationException(
				$request, $validator
			);
		}
		Auth::login($this->create($request->all()));
		//$user_data = new User;
		//$user_data->l_name = $request->l_name;
		//$user_data = User::create($request->all());
		//$user_data->save();

		return redirect($this->redirectPath());   //redirect controller set in protected $redirectTo = '/';
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 * @return User
	 */

	protected function create(array $data){
		return User::create([
			'f_name' => $data['f_name'],
			'l_name' => $data['l_name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'location_id' => $data['location_id'],
			'category_id' => $data['category_id'],
		]);
	}
}
