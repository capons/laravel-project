@extends('main')

@section('title', 'Register')

@section('sidebar')

@stop

@section('content')

	<div>
		<form action="{{action('Auth\AuthController@postRegister')}}" method="post">
			First name<input type="text" name="f_name"><br>
			Last name<input type="text" name="l_name"><br>
			Locality<select name="location_id">
				<option value="0" selected>0</option>
			</select><br>
			Email address<input type="email" name="email" value="{{ old('email') }}"><br>
			Password<input type="text" name="password"><br>
			<input type="checkbox" name="category_id" value="0">
			Category
			<input type="submit">
			{!! csrf_field() !!}
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