@extends('admin')

@section('title', 'Заголовок страницы')

@section('sidebar')
@stop

@section('content')
	<?php
	$path = base_path('App/function/dataTable/php/DataTables.php');
	include($path);

	?>

	<table data-order='[[ 1, "asc" ]]' data-page-length='10' id="users" class="display" cellspacing="0" width="100%">
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

	@if (count($users) > 0)  <!-- if $users have data -->
		<script type="text/javascript">
				$(document).ready(function() { //display all users data by DataTable library
					$('#users').DataTable( {
						select: true,
						ajax : "<?php echo Config::get('app.url'); ?>/admin/users",
						"columns": [
							{ "data": "f_name" },
							{ "data": "email" }
							//{ "data": "location" }
						],
						"buttons": [
							{
								text: 'Select All',
								className: 'btn-success',
								action: function ( e, dt, node, config ) {
									alert( 'Button activated');
								}
							},
							{
								text: 'Deselect All',
								className: 'btn-success',
								action: function ( e, dt, node, config ) {
									alert( 'Button activated');
								}
							},
							{
								text: 'Refresh',
								className: 'btn-success',
								action: function ( e, dt, node, config ) {
									alert( 'Button activated');
								}
							}
						]
						//data:data
					} );
			});
		</script>
	@endif
@stop