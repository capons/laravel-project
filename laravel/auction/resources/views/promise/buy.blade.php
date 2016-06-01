@extends('main')

@section('title', 'Buy a promise')

@section('sidebar')
@stop

@section('content')
	<ul>
		@foreach($category as $v)
			<li class="select-category" data-id="{{$v['id']}}">{{$v['name']}}</li>
		@endforeach
	</ul>
	<div id="list_promise">

	</div>

	<script type="text/javascript" src="/js/promise/buy.js"></script>
@stop