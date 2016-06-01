@extends('main')

@section('title', 'Request a promise')

@section('sidebar')
@stop

@section('content')
	<form action="">
		<select id="cat" name="category_id" class="input_form">
			@foreach($category as $v)
				<option value="{{$v['id']}}">{{$v['name']}}</option>
			@endforeach
		</select>
		price <input class="input_form" type="number" name="price" value="100.0">
		time <input class="input_form" type="text" name="time" value="31-12-2015">
		<input type="button" id="btn_form" value="Save">
		<input type="hidden" name="_token" class="input_form" value="{{ csrf_token() }}">
	</form>
	<script type="text/javascript" src="/js/promise/request.js"></script>
@stop