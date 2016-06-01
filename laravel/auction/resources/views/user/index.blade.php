@extends('main')

@section('title', 'Заголовок страницы')

@section('sidebar')
	<!--can add sidebar section -->
@stop

@section('content')
	<a href="{{ action('Auth\AuthController@getLogout') }}">Logout</a><br>
	<a href="{{ action('Auth\AuthController@getRegister') }}">Register</a><br>
	<a href="{{ action('Auth\AuthController@getLogin') }}">Login</a><br>
	<a href=""></a><br>
	<a href=""></a><br>
@stop
