<div id="notifications" class="notificationspanel">
    @if(count(auth()->user()->notifications()->orderBy('is_read')->get()) == 0)
    <div id="no-notifications" class="notification-content">You don't have any notifications.</div>
    @else 
    @foreach (auth()->user()->notifications()->orderBy('is_read')->get() as $notification)
    <div onmouseover="notification(this.dataset.notificationid)" data-notificationid="{{ $notification->id }}" id="{{ $notification->id }}" class="notification" style="{{ $notification->is_read == 1 ? 'background-color: #cacaca' : '' }}">
        <div class="notification-content">
        @if ($notification->changeInPrice)
                    <a href="{{route('showProductDetails', $notification->changeInPrice->product->id)}}"><img src="{{ file_exists(public_path("storage/products/" . $notification->changeInPrice->product->id . "_1.png")) ? asset( 'storage/products/' .$notification->changeInPrice->product->id. '_1.png' ) : asset('images/products/default.png') }}" alt="" class="notification-image"></a>
                    <div>{{ number_format($notification->changeInPrice->product->price - (($notification->changeInPrice->product->price * $notification->changeInPrice->product->discount->percentage)/100),2) }}€<span class="notification-product-price"> {{ $notification->changeInPrice->product->price }}€</span></div>
                    <div class="notification-message">
                        <span class="product-name">{{ $notification->changeInPrice->product->product_name }}</span> from your wishlist is now on sale!
                    </div>
        @elseif ($notification->itemAvailability)
        @php $notif = $notification->itemAvailability @endphp
                    <a href="{{route('showProductDetails', $notif->product->id)}}"><img src="{{ file_exists(public_path("storage/products/" . $notif->product->id . "_1.png")) ? asset( 'storage/products/' .$notif->product->id . '_1.png' ) : asset('images/products/default.png') }}" alt="" class="notification-image"></a>
                    <div class="notification-message">
                        @if($notification->notification_text == 'LAST ITEM AVAILABLE')
                        There's only one <span class="product-name">{{ $notif->product->product_name }}</span> left in stock!
                        @else
                        <span class="product-name">{{ $notif->product->product_name }}</span> from your wishlist is now available!
                        @endif
                    </div>
        @elseif ($notification->paymentApproved)
        @php $notif = $notification->paymentApproved @endphp
                    <i class="fa fa-check" style="font-size: 2.5em; color: var(--primary-color);"></i>
                    <div class="notification-message">
                        Payment Confirmed!
                    </div>
        @elseif ($notification->likedReview)
        @php $notif = $notification->likedReview @endphp
                    <a href="{{route('showProductDetails', $notif->review->product->id)}}"><img src="{{ file_exists(public_path("storage/products/" . $notif->review->product->id . "_1.png")) ? asset( 'storage/products/' .$notif->review->product->id . '_1.png' ) : asset('images/products/default.png') }}" alt="" class="notification-image"></a>
                    <div class="notification-message">
                        Someone liked your review on <span class="product-name">{{ $notif->review->product->product_name}}</span>!
                    </div>
        @elseif ($notification->changeOfOrder)
        @php $notif = $notification->changeOfOrder @endphp
                    @if($notification->notification_text == 'Shipping')  
                    <i class="fa fa-truck" style="font-size: 2.5em; color: black;"></i>
                    <div class="notification-message">
                        Order status: <span class="product-name">{{ $notification->notification_text }}</span>
                    </div>
                    @elseif ($notification->notification_text == 'Canceled')
                    <i class="fa fa-times" style="font-size: 2.5em; color: red;"></i>
                    <div class="notification-message">
                        Order status: <span class="product-name">{{ $notification->notification_text }}</span>
                    </div>
                    @elseif ($notification->notification_text == 'Received')
                    <i class="fa fa-check-circle" style="font-size: 2.5em; color: var(--primary-color);"></i>
                    <div class="notification-message">
                        Order status: <span class="product-name">{{ $notification->notification_text }}</span>
                    </div>
                    @elseif ($notification->notification_text == 'Waiting for payment')
                    <i class="fa fa-clock-o" style="font-size: 2.5em; color: var(--primary-color);"></i>
                    <div class="notification-message">
                        Order status: <span class="product-name">{{ $notification->notification_text }}</span>
                    </div>
                    @endif
        @endif
                </div>
                <button onclick="deleteNotification(this.dataset.notificationid)" data-notificationid="{{ $notification->id }}" class="notification-delete"><i class="fa fa-trash"></i></button>
            </div>
    @endforeach
    @endif
</div>