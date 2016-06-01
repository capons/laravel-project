@extends('admin')

@section('title', 'Заголовок страницы')

@section('sidebar')
@stop

@section('content')

	<script src="{!! asset('/AdminLTE/plugins/datatables/jquery.dataTables.min.js') !!}" type="text/javascript"></script>
	<link href="{!! asset('/AdminLTE/plugins/datatables/jquery.dataTables.min.css') !!}" rel="stylesheet" type="text/css">
	<table id="users" class="display" cellspacing="0" width="100%">
		<thead>
		<tr>
			<th>Name</th>
			<th>email</th>
			<th>Location</th>
		</tr>
		</thead>
		<tfoot>
		</tfoot>
	</table>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#users').DataTable( {
				"ajax": "/admin/users",
				"columns": [
					{ "data": "f_name" },
					{ "data": "email" },
					{ "data": "location" }
				]
			} );
		} );
	</script>
@stop