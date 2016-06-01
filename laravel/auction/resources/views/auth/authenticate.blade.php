@extends('main')

@section('title', 'login')

@section('sidebar')
@stop

@section('content')
<div>
	<form method="POST" action="{{ url('/auth/login')}}">
		{!! csrf_field() !!}

		<div>
			Email
			<input type="email" name="email" value="{{ old('email') }}">
		</div>

		<div>
			Password
			<input type="password" name="password" id="password">
		</div>

		<div>
			<input type="checkbox" name="remember"> Remember Me
		</div>

		<div>
			<button type="submit">Login</button>
		</div>
	</form>
</div>
@if (Session::get('errors'))
	<div class="alert alert-dismissable alert-warning">
		<h4>Uwaga!</h4>
		<ul>
			@foreach (Session::get('errors')->all() as $error)
				<li>{!! $error !!}</li>
			@endforeach
		</ul>
	</div>
@endif
@stop