<div id="notifications" class="notificationspanel">
    @if(count(auth()->user()->notifications()->orderBy('is_read')->get()) == 0)
    <div class="notification-content">You don't have any notifications.</div>
    @else 
    @foreach (auth()->user()->notifications()->orderBy('is_read')->get() as $notification)
    <div onmouseover="notification(this.dataset.notificationid)" data-notificationid="{{ $notification->id }}" id="{{ $notification->id }}" class="notification" style="{{ $notification->is_read == 1 ? 'background-color: #cacaca' : '' }}">
        <div class="notification-content">
        @if ($notification->changeInPrice)
                    <a href="{{route('showProductDetails', $notification->changeInPrice->product->id)}}"><img src="{{ asset( 'images/products/' . $notification->changeInPrice->product_id . '.png' ) }}" alt="" class="notification-image"></a>
                    <div>{{ number_format($notification->changeInPrice->product->price - (($notification->changeInPrice->product->price * $notification->changeInPrice->product->discount->percentage)/100),2) }}€<span class="notification-product-price"> {{ $notification->changeInPrice->product->price }}€</span></div>
                    <div class="notification-message">
                        <span class="product-name">{{ $notification->changeInPrice->product->product_name }}</span> from your wishlist is now on sale!
                    </div>
        @elseif ($notification->itemAvailability)
        @php $notif = $notification->itemAvailability @endphp
                    <a href="{{route('showProductDetails', $notif->product->id)}}"><img src="{{ asset( 'images/products/' . $notif->product->id . '.png' ) }}" alt="" class="notification-image"></a>
                    <div class="notification-message">
                        <span class="product-name">{{ $notif->product->product_name }}</span> from your wishlist is now available!
                    </div>
        @elseif ($notification->paymentApproved)
        @php $notif = $notification->paymentApproved @endphp
                    <i class="fa fa-check" style="font-size: 2.5em; color: var(--primary-color);"></i>
                    <div class="notification-message">
                        Payment Confirmed!
                    </div>
        @elseif ($notification->likedReview)
        @php $notif = $notification->likedReview @endphp
                    <a href="{{route('showProductDetails', $notif->review->product->id)}}"><img src="{{ asset( 'images/products/' . $notif->review->product->id . '.png' ) }}" alt="" class="notification-image"></a>
                    <div class="notification-message">
                        <span class="product-name">{{ $notif->review->user->username }}</span> liked your review on <span class="product-name">{{ $notif->review->product->product_name}}</span>!
                    </div>
        @elseif ($notification->changeOfOrder)
        @php $notif = $notification->changeOfOrder @endphp
                    @if($notif->order->order_status == 'Shipping')  
                    <i class="fa fa-truck" style="font-size: 2.5em; color: black;"></i>
                    @elseif ($notif->order->order_status == 'Canceled')
                    <i class="fa fa-times" style="font-size: 2.5em; color: red;"></i>
                    @elseif ($notif->order->order_status == 'Received')
                    <i class="fa fa-check-circle" style="font-size: 2.5em; color: var(--primary-color);"></i>
                    @endif
                    <div class="notification-message">
                        Order status: <span class="product-name">{{ $notif->order->order_status }}</span>
                    </div>
        @endif
                </div>
                <button onclick="deleteNotification(this.dataset.notificationid)" data-notificationid="{{ $notification->id }}" class="notification-delete"><i class="fa fa-trash"></i></button>
            </div>
    @endforeach
    @endif
</div>