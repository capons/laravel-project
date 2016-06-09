<!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="{!! asset('public/css/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css">
	<link href="{!! asset('public/css/bootstrap/css/bootstrap-theme.min.css') !!}" rel="stylesheet" type="text/css">
	<link href="{!! asset('public/css/style.css') !!}" rel="stylesheet" type="text/css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript" src="{!! asset('public/js/WCL/main.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/js/WCL/interface.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('public/js/system.js') !!}"></script>

	<!--style js -->
	<script type="text/javascript" src="{!! asset('/js/main.js') !!}"></script>
</head>
<body>
@section('sidebar')
	Это - главный сайдбар.
@show
	@yield('content')
</body>
</html>
