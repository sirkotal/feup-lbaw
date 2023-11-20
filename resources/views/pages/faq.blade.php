@extends('layouts.app')

@include('headers.header')
@section('header')
    @yield('header')
@endsection

@section('content')
<h1 class="faq-header">Frequently Asked Questions</h1>
<div id="faq">
	<section class="faq-question">
		<section class="faq-question-head">
			<h2>What is Cappuccino?</h2>
			<button class="dropdown-button" aria-expanded="false">
		        <span class="down-icon"><i class='fa fa-caret-square-o-down'></i></span>
	        </button>
		</section>
		<p>Cappuccino is a project aimed at developing an online grocery shop, marketed for users that wish to do their grocery shopping online in a more convenient, user-friendly way.</p>
	</section>
	<section class="faq-question">
		<section class="faq-question-head">
			<h2>Can I leave feedback on the products I have purchased?</h2>
			<button class="dropdown-button" aria-expanded="false">
                <span class="down-icon"><i class='fa fa-caret-square-o-down'></i></span>
	        </button>
		</section>
		<p>To maintain transparency and trust in our products and services, customers can also provide reviews and comments on any item, ensuring that their valuable feedback is shared with our community.</p>
	</section>	
	<section class="faq-question">
		<section class="faq-question-head">
			<h2>How can I contact customer support?</h2>
			<button class="dropdown-button" aria-expanded="false">
                <span class="down-icon"><i class='fa fa-caret-square-o-down'></i></span>
	        </button>
		</section>
		<p>You can contact customer support by emailing us at pescator@cappuccino.com or by calling our toll-free number at (505) 503-4455.</p>
	</section>	
	<section class="faq-question">
		<section class="faq-question-head">
			<h2>Do you offer free shipping?</h2>
			<button class="dropdown-button" aria-expanded="false">
                <span class="down-icon"><i class='fa fa-caret-square-o-down'></i></span>
	        </button>
		</section>
		<p>Cappuccino offers free shipping on all orders over $80.</p>
	</section>
</div>
@endsection