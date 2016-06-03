@extends('main')

@section('title', 'Заголовок страницы')

@section('sidebar')
	<!--can add sidebar section -->
@stop

@section('content')

	<a href="{{ action('Auth\AuthController@getLogout') }}">Logout</a><br>
	<a href="{{ action('Auth\AuthController@getRegister') }}">Register</a><br>
	@if (!Session::has('user_auth_mess')) <!--if user login hide block -->
		<a href="{{ action('Auth\AuthController@getLogin') }}">Login</a><br>
	@endif
	<a href=""></a><br>
	<a href=""></a><br>
	<!--User information -->
	@if(Session::has('user-info'))
		<div class="alert-box success">
			<h2>{{ Session::get('user-info') }}</h2>
		</div>
		@endif
	<!--End user information -->
	<!--User login info -->
		@if (Session::has('user_auth_mess'))
			<?php
			$user = Session::get('user_auth_mess');
			echo '<pre>';
			print_r($user);
			echo '</pre>';
			?>
		@endif
	<!-- ./user login info -->
@stop
