<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show()
    {
        if (Auth::check()) {
            $userId = Auth::user()->id;
            $unreadNotifications = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->get();
            $notifications = $unreadNotifications->map(function ($lastNotification) {
                if($lastNotification->changeInPrice){
                    return [
                        'success' => true,
                        'text' => $lastNotification->notification_text,
                        'notification_id' => $lastNotification->id,
                        'is_read' => $lastNotification->is_read,
                        'type' => 'change_in_price',
                        'product_id' => $lastNotification->changeInPrice->product->id,
                        'product_name' => $lastNotification->changeInPrice->product->product_name,
                        'price' => $lastNotification->changeInPrice->product->price,
                        'discount' => $lastNotification->changeInPrice->product->discount ? $lastNotification->changeInPrice->product->discount->percentage : 0, 
                        'product_path' => file_exists(public_path("storage/products/" . $lastNotification->changeInPrice->product->id . "_1.png")) ? true : false
                    ];
                }
                else if($lastNotification->itemAvailability){
                    return [
                        'success' => true,
                        'text' => $lastNotification->notification_text,
                        'notification_id' => $lastNotification->id,
                        'is_read' => $lastNotification->is_read,
                        'type' => 'item_availability',
                        'product_id' => $lastNotification->itemAvailability->product->id,
                        'product_name' => $lastNotification->itemAvailability->product->product_name,
                        'product_path' => file_exists(public_path("storage/products/" . $lastNotification->itemAvailability->product->id . "_1.png")) ? true : false
    
                    ];
                }
                else if($lastNotification->likedReview){
                    return [
                        'success' => true,
                        'text' => $lastNotification->notification_text,
                        'notification_id' => $lastNotification->id,
                        'is_read' => $lastNotification->is_read,
                        'type' => 'liked_review',
                        'product_id' => $lastNotification->likedReview->review->product->id,
                        'product_name' => $lastNotification->likedReview->review->product->product_name,
                        'product_path' => file_exists(public_path("storage/products/" . $lastNotification->likedReview->review->product->id . "_1.png")) ? true : false
                    ];
                }
            });
        }
        
        return response()->json(['notifications'=> $notifications]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {   
        if(Auth::check()){
            $notification = Notification::find($id);
            $notification->delete();
            return response()->json([ 'success' => true, 'notification_id' => $id ]);
        }
        return response()->json([ 'success'=> false ] );
    }
}
