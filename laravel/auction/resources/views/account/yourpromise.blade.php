@extends('main')

@section('title', 'Promise')

@section('sidebar')
@stop

@section('content')
	<div class="container">
		<div class="row">
			@foreach($request as $val)
				<div>
					<div>
						<div>{{$val['title']}}</div>
						<div>{{$val['desc']}}</div>
						<div>${{$val['price']}}</div>
					</div>
					<div>
						<div>{{$val['status']}}</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>
	<script type="text/javascript" src="/js/account/yourpromise.js"></script>
@stop