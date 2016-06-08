<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::controller('users', 'UserController');
Route::get('/', 'UserController@getIndex');
/*Route::get('user/register', 'UserController@register');
Route::get('user/index', 'UserController@index');*/
//Route::post('auth/create', 'Auth\AuthController@postRegister');
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister'); //view auth/register
Route::post('auth/register', 'Auth\AuthController@postRegister'); //receive data from registration form

//Route::controller('user', 'UserController');
//Route::controller('promise', ['middleware' => 'auth', 'uses' => 'PromiseController']);

Route::get('home','UserController@getIndex'); //maby page for quest

Route::group(['middleware' => ['auth']], function(){
    Route::get('/promise/index', 'PromiseController@getIndex');
    Route::get('/promise/validation', 'PromiseController@validation');
    Route::get('/promise/request', 'PromiseController@pageRequest');
    Route::get('/promise/buy', 'PromiseController@pageBuy');
    Route::get('/promise/profile/{id}', 'PromiseController@pageProfile');
    Route::get('/promise/buypromise', 'PromiseController@pageBuypromise');

    //profile route
    Route::get('/account/broughtpromise', 'AccountController@pageBroughtpromise');
    Route::get('/account/otherpromise', 'AccountController@pageOtherpromise');
    Route::get('/account/sellpromise', 'AccountController@pageSellpromise');
    Route::get('/account/yourpromise', 'AccountController@pageYourpromise');

    //promise route
    Route::post('/promise/add', 'PromiseController@add');
    Route::post('/promise/addrequest', 'PromiseController@addRequest');
    Route::post('/promise/getdata', 'PromiseController@getData');
    Route::post('/promise/buy', 'PromiseController@buy');
    Route::post('/promise/check', 'PromiseController@check');
    Route::post('/promise/getpromisebycategory', 'PromiseController@getPromiseByCategory');

    Route::get('/promise/sell', 'PromiseController@pageSell');
    Route::get('/home','UserController@getIndex');

    Route::get('/user/getfile','UserController@uploadedFile');

 

});

Route::group(['middleware' => ['auth']], function() {
    //Route::get('/admin', 'AdminController@users'); //old route
    /*Users route*/
    Route::get('/admin/users','AdminUsersController@users'); //i add
    Route::post('/admin/users','AdminUsersController@modify'); //i add
    Route::get('/admin/users/new','AdminUsersController@newUser');
    /* ./Users route*/
    /* Category route*/
    Route::get('/admin/category','AdminCategoryController@getCategory');
    Route::post('/admin/category','AdminCategoryController@modify');
    /* ./Category route*/
    /* ./Location*/
    Route::get('/admin/location','AdminLocationController@getIndex');
    Route::post('/admin/location','AdminLocationController@modify');
    /* ./Location*/
    Route::get('/admin/pagepromise', 'AdminController@pagePromise');

   // Route::get('/admin/users', 'AdminController@users'); //old route
   // Route::get('/admin/promise', 'AdminController@promise'); old route
});

/*Route::get('/', function () {
    return view('welcome');
});*/
