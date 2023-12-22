@extends('layouts.app')

@section('styles')
    <link href="{{ url('css/user_details.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/admin_reviews.js') }}" defer></script>
    <script type="text/javascript" src="{{ URL::asset('js/admin_orders.js') }}" defer></script>
    <script type="text/javascript" src="{{ URL::asset('js/user_details.js') }}" defer></script>
@endsection

@section('title', 'Details')

@include('headers.simple-header')
@section('header')
    @yield('header')
@endsection

@section('content')

<div id="content" class="d-flex justify-content-left">
    <div id='info'>
        <div class="card">
            <div id="Photo">
                <img class="card-img-top" src="{{ $user->user_path == 'def' ?   asset('images/' . $user->user_path . '.png') :asset('storage/images/' . $user->user_path . '.png') }}" alt="User image">
            </div>
            <div class="card-body">
                <h5 id="Username" class="card-title">{{ $user->username }}</h5>
                <p class="card-text"><i class="fa fa-envelope" aria-hidden="true"></i>  {{ $user->email }}</p>
                <p class="card-text"><i class="fa fa-calendar" aria-hidden="true"></i>  {{ $user->date_of_birth }}</p>
                <p class="card-text"><i class="fa fa-phone" aria-hidden="true"></i>  {{ $user->phone_number }}</p>
            </div>
            <div class="card-body">
                <h5>INFO</h5>
                <div class="d-flex justify-content-left">
                    <p class="mr-2">Times blocked:</p>
                    <p>{{$timesblocked}}</p>
                </div>
                <div class="d-flex justify-content-left">
                    <p class="mr-2">Favourite Product: </p>
                    @if($favourite != NULL && $favourite->id != NULL) 
                    <a href="{{ route('showProductDetails', ['id' => $favourite->id]) }}">{{$favourite->product_name}}</a>
                    @else
                    <p>Unknown</p>
                    @endif
                </div>
            </div>
            <div id="admin-users" class="d-flex justify-content-center"> @if($blocked)<button class="unblock"><i class="bi bi-lock-fill"></i></button>@else<button class="block"><i class="bi bi-unlock-fill"></i></button>@endif<button class="delete"><i class="bi bi-trash3-fill"></i></button></div>
        </div>
    </div>
    <div id="div_orders_reports" class="d-flex flex-column justify-content-left">
        <h1>Orders</h1>
        @if($orders->count() > 0)
        <table id="orders" class="table table-striped">
            <thead>
                <tr class='header'>
                    <th scope="col" class="text-center align-middle">Initial Date</th>
                    <th scope="col" class="text-center align-middle">Address</th>
                    <th scope="col" class="text-center align-middle">Price</th>
                    <th scope="col" class="text-center align-middle">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class='orderInfo' id='showOrderInfo'>
                    <td class="text-center align-middle">{{$order->order_date}}</td>
                    <td class="text-center align-middle">{{$order->address}}</td>
                    <td class="text-center align-middle">{{$order->total}} €</td>
                    <td class="text-center align-middle">{{$order->order_status}}</td>
                    <td class="text-center align-middle p-0"><div class="d-flex justify-content-center"><button class="edit_order"><i class="bi bi-pencil-fill"></i></button><div></td>
                </tr>
                <tr class='orderInfo hidden' id='editOrderInfo'>
                    <td class="text-center align-middle">{{$order->order_date}}</td>
                    <td class="text-center align-middle">{{$order->address}}</td>
                    <td class="text-center align-middle">{{$order->total}} €</td>
                    <td id='id' style="display: none">{{ $order->id }}</td>
                    <td class="text-center align-middle">
                        <select name="orders">
                            <option>Waiting for payment</option>
                            <option>Payment Approved</option>
                            <option>Shipping</option>
                            <option>Received</option>
                            @if($order->order_status != 'Shipping' && $order->order_status != 'Received')
                                <option>Canceled</option>
                            @endif
                        </select>
                    </td>
                    <td class="text-center align-middle p-0"><div class="d-flex justify-content-center"><button class="save_order"><i class="bi bi-floppy-fill"></i></button><div></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p>THIS USER NEVER MADE AN ORDER</p>
        @endif
        <h1>Reports</h1>
        @if($reports->count() > 0)
            <table id="admin-reviews" class="table table-striped">
                <thead>
                    <tr class='header'>
                        <th scope="col" class="text-center align-middle">Date</th>
                        <th scope="col" class="text-center align-middle">Reason</th>
                        <th scope="col" class="text-center align-middle">User</th>
                        <th scope="col" class="text-center align-middle">Product</th>
                        <th scope="col" class="text-center align-middle">Review</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr class='reportInfo'>
                        <td class="text-center align-middle">{{$report->report_date}}</td>
                        <td class="text-center align-middle">{{$report->reason}}</td>
                        @if(!$report->user->is_deleted)
                            <td class="text-center align-middle"><a href="{{ route('AdminUsersDetails', ['id' => $report->review->user->id]) }}"><img class="mr-2" src="{{ asset('storage/images/' . $report->review->user->user_path . '.png') }}">{{$report->review->user->username}}</a></td>
                        @else
                            <td class="text-center align-middle">[deleted-user]</td>
                        @endif
                        <td class="text-center align-middle">
                            @if(file_exists(public_path("storage/products/" . $report->review->product->id . "_1.png")))
                                <img class="mr-2" src="{{ asset('storage/products/' . $report->review->product->id . '_1.png') }}" alt="{{ $report->review->product->product_name }}">
                            @else
                                <img class="mr-2" src="{{ asset('image/products/default.png') }}" alt="{{ $report->review->product->product_name }}">
                            @endif    
                            <a href="{{ route('showProductDetails', ['id' => $report->review->id]) }}">{{$report->review->product->product_name}}</a>
                        </td>
                        <td class="review text-center align-middle">review <i class="bi bi-arrow-down-circle-fill"></i></td>
                        <td id='review_id' style="display: none">{{$report->review_id}}</td>
                        <td id='user_id' style="display: none">{{$report->user_id}}</td>
                        <td class="text-center align-middle p-0">
                            <div class="d-flex justify-content-center position-relative">
                                <button class="erase"><i class="bi bi-x-lg"></i></button>
                                <button class="delete"><i class="bi bi-trash3-fill"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>THIS USER DOES NOT HAVE REPORTS</p>
        @endif
    </div>
    <div id="div_reviews" class="d-flex flex-column justify-content-left">
        <h1>Reviews</h1>
        @if($reviews->count() > 0)
            <table id="reviews" class="table table-striped">
                <thead>
                    <tr class='header'>
                        <th scope="col" class="text-center align-middle"></th>
                        <th scope="col" class="text-center align-middle">Date</th>
                        <th scope="col" class="text-center align-middle">Title</th>
                        <th scope="col" class="text-center align-middle">Text</th>
                        <th scope="col" class="text-center align-middle">Product</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr class='reportInfo'>
                        @if($review->review_id != null)
                            <td class="text-center align-middle"><i class="bi bi-exclamation-triangle-fill"></i></td>
                        @else
                            <td class="text-center align-middle"><i class="bi bi-check2"></i></td>
                        @endif
                        <td class="text-center align-middle">{{$review->review_date}}</td>
                        <td class="text-center align-middle">{{$review->title}}</td>
                        <td class="text-center align-middle">{{$review->review_text}}</td>
                        <td id='review_id' style="display: none">{{$review->id}}</td>
                        <td class="text-center align-middle">
                            @if(file_exists(public_path("storage/products/" . $review->product->id . "_1.png")))
                                <img class="mr-2" src="{{ asset('storage/products/' . $review->product->id . '_1.png') }}" alt="{{ $review->product->product_name }}">
                            @else
                                <img class="mr-2" src="{{ asset('images/products/default.png') }}" alt="{{ $review->product->product_name }}">
                            @endif  
                            <a href="{{ route('showProductDetails', ['id' => $review->id]) }}">{{$review->product->product_name}}</a>
                        </td>
                        <td class="text-center align-middle p-0">
                            <div class="d-flex justify-content-center position-relative">
                                <button class="erase"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>THIS USER DOES NOT HAVE REVIEWS</p>
        @endif
    </div>
    @foreach($reports as $report)
        <div id="review_{{$report->review_id}}_{{$report->user_id}}" class="review_details" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="review_header">
                <div class="d-flex justify-content-between">
                    <div class=" test d-flex justify-content-center mr-2">
                        <h5>Title:</h5>
                        <p class="">{{$report->review->title}}</p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <h5>Date:</h5>
                        <p>{{$report->review->review_date}}</p>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="d-flex justify-content-center">
                        <h5>Rating:</h5>
                        <p>{{$report->review->rating}}</p>
                    </div>
                    <div class="d-flex justify-content-center">
                        <h5>Upvotes:</h5>
                        <p>{{$report->review->upvote_count}}</p>
                    </div>
                </div>
            </div>
            <div class="review_body">
                <p class="review_text">{{$report->review->review_text}}</p>
            </div>
        </div>
    @endforeach
</div>
@endsection
