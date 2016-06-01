<!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?php echo base_path(); ?>/public/css/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_path(); ?>/public/css/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_path(); ?>/public/css/style.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="<?php echo base_path(); ?>/public/js/WCL/main.js"></script>
	<script type="text/javascript" src="<?php echo base_path(); ?>/public/js/WCL/interface.js"></script>
	<script type="text/javascript" src="<?php echo base_path(); ?>/public/js/system.js"></script>
</head>
<body>
@section('sidebar')
	Это - главный сайдбар.
@show
	@yield('content')
</body>
</html>
