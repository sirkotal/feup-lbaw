<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class StripeController extends Controller
{
    public function handleSuccess(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::findOrFail($orderId);

        $order->update(['order_status' => 'Shipping']);

        return redirect()->route('user')->with('success', 'Order placed successfully!');
    }
    public function handleCancel(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::findOrFail($orderId);
    
        $order->update(['order_status' => 'Canceled']);
    
        foreach ($order->products as $product) {
            $quantity = $product->pivot->quantity;
    
            $user = auth()->user();
            $user->shoppingCart()->attach($product->id, ['quantity' => $quantity]);
    
            $product->stock += $quantity;
            $product->save();
        }
    
        return redirect()->route('user')->with('error', 'Order canceled');
    }
}
