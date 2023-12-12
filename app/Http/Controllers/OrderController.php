<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\shoppingCart;
use App\Models\paymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function createOrder(Request $request)
    {
        try {
            $this->authorize('createOrder', Order::class);
            \Stripe\Stripe::setApiKey(config('stripe.sk'));

            DB::beginTransaction();

            $user = auth()->user();
    
            if ($user->shoppingCart->isEmpty()) {
                throw new \Exception('Shopping cart is empty');
            }
    
            $formData = $request->all();
    
            $order = new Order([
                'order_date' => now(),
                'item_quantity' => 0,
                'order_status' => 'Waiting for payment',
                'total' => 0,
                'address' => $formData['address'],
                'country' => $formData['country'],
                'zip_code' => $formData['zip'],
                'city' => $formData['city'],
                'user_id' => $user->id,
            ]);
    
            $order->save();
    
            $shoppingCartItems = $user->shoppingCart;
    
            foreach ($shoppingCartItems as $cartItem) {
                if ($cartItem->stock < $cartItem->pivot->quantity) {
                    throw new \Exception('Insufficient stock for product: ' . $cartItem->product_name);
                }
    
                $order->products()->attach($cartItem->id, [
                    'quantity' => $cartItem->pivot->quantity,
                    'price_bought' => $cartItem->price,
                ]);
    
                $order->item_quantity += $cartItem->pivot->quantity;
                $order->total += ($cartItem->pivot->quantity * $cartItem->price);
    
                $cartItem->stock -= $cartItem->pivot->quantity;
                $cartItem->save();
    
                shoppingCart::where([
                    'user_id' => auth()->id(),
                    'product_id' => $cartItem->id,
                ])->delete();
            }
    
            $order->save();

            /* PAYMENT TRANSACTION TO BE CHANGED */

            $paymentTransaction = new paymentTransaction([
                'method' => 'Credit Card',
                'payment_status' => 'Approved',
                'order_id' => $order->id,
            ]);
    
            $paymentTransaction->save();

            $lineItems = [];

            foreach ($shoppingCartItems as $cartItem) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $cartItem->product_name,
                        ],
                        'unit_amount' => $cartItem->price * 100,
                    ],
                    'quantity' => $cartItem->pivot->quantity,
                ];
            }

            DB::commit();

            $paymentIntent = \Stripe\Checkout\Session::create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('stripe.success', ['order_id' => $order->id]),
                'cancel_url' => route('stripe.cancel', ['order_id' => $order->id]),
                'locale' => 'pt',
            ]);

            return redirect()->away($paymentIntent->url);

            /*if ($paymentIntent->status !== 'succeeded') {
                throw new \Exception('Stripe payment failed');
            }
    
            return redirect()->route('user')->with('success', 'Order placed successfully!');*/
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['transaction-error' => 'Transaction failed: ' . $e->getMessage()]);
        }
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    public function edit(Request $request)
    {
        $this->authorize('editProduct', User::class);

        $request->validate([
            'order_status' => 'required',
        ]);

        $order = Order::findOrFail($request->input('id'));

        $order->update([
            'order_status' => $request->input('order_status'),
        ]);

        return response()->json(['message' => 'ok']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
