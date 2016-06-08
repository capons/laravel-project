@extends('main')

@section('title', 'Promise')

@section('sidebar')
@stop

@section('content')
<script type="text/javascript" src="<?php echo base_path(); ?>/public/js/promise/buypromise.js"></script>
    <?php
    echo 'id пользователя '.\Illuminate\Support\Facades\Auth::user()->id;
    ?>
@stop