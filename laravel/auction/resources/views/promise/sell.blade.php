@extends('main')

@section('title', 'Заголовок страницы')

@section('sidebar')
@stop

@section('content')
	<div class="container">
		<div class="row">
			<button id="btn_buy" class="btn">BUY NOW PROMISE</button>
			<button id="btn_auction" class="btn select">AUCTION PROMISE</button>
		</div>
		<div class="row">
			<div>SELECT A CATEGORY {{\App\model\DB\Category::getSelect()}}</div>
			<div>SELECT YOUR LOCALITY {{\App\model\DB\Location::getSelect()}}</div>
			<div>TITLE OF PROMISE <input type="text" class="input_form" name="title"></div>
			<div class="auction">NUMBER OF WINNWRS FOR THIS AUCTION <input type="text" class="input_form" name="winners"></div>
			<div class="auction">DATE AUCTION CLOSES<input type="text" name="time" class="input_form"></div>
			<div class="buy">NUMBER OF TIMES THIS PROMISE IS AVAILABLE FOR SALE <input type="text" name="shows" class="input_form"></div>
			<div><div>DETAILS/DESCRIPTION OF WHAT THE PROMISE INCLUDED</div><textarea name="desc" class="input_form"></textarea></div>
			<div><div>TERMS & CONDITIONS OF THE PROMISE</div><textarea name="terms" class="input_form"></textarea></div>
			<div><span>UPLOAD</span> A PHOTO OR <span>SELECT</span> ONE OF OURS</div>
			<div>FEATURED PROMISE? <span>Do you think this promise has what it takes to be featured on our home page</span><input type="checkbox" name="featured" class="input_form"></div>
			<div>SET YOUR START PRICE <input type="text" name="price" class="input_form"></div>
			<div><button id="submit" class="btn">SUBMIT</button></div>
		</div>
	</div>
		<input style="display: none" type="file" name="file" class="input_form" >
		<input type="hidden" name="_token" class="input_form" value="{{ csrf_token() }}">
	<script type="text/javascript" src="/js/promise/sell.js"></script>
@stop