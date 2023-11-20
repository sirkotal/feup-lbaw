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

    public function createOrder($id, Request $request)
    {
        try {
            $this->authorize('createOrder', Order::class);

            DB::beginTransaction();
    
            $user = auth()->user();
    
            if ($user->shoppingCart->isEmpty()) {
                throw new \Exception('Shopping cart is empty');
            }
    
            $formData = $request->all();
    
            $order = new Order([
                'order_date' => now(),
                'item_quantity' => 0,
                'order_status' => 'Shipping',
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
    
            $paymentTransaction = new paymentTransaction([
                'method' => $formData['payment_method'],
                'payment_status' => 'Approved',
                'order_id' => $order->id,
            ]);
    
            $paymentTransaction->save();
    
            DB::commit();
    
            return redirect()->route('user')->with('success', 'Order placed successfully!');
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
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
