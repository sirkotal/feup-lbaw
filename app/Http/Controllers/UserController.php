<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use App\Models\blockAction;
use App\Models\Order;
use App\Models\Category;
use App\Models\orderedProduct;
use App\Models\Product;
use App\Models\paymentTransaction;
use App\Models\Discount;
use App\Models\Report;
use App\Models\Brand;
use App\Models\Review;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{   
    
    public function show()
    {   
        $this->authorize('show', User::Class);
        $user = Auth::user(); 
        $orders = Order::where('user_id', $user->id)->get();
        $orders = $orders->reverse();
        $history = [];
        foreach ($orders as $order){
            $history[] = [$order,$order->products()->get()];
        }
        
        return view('pages.profile')->with(['user' => $user, 'orders' => $history]);
    }

    public function showEdit()
    {
        $this->authorize('show', User::Class);
        return view('pages.edit_profile');
    }

    public function userDetails($id)
    {   
        $user = User::where('id', $id)->first();
        $orders = Order::where('user_id', $id)->get();
        $reviews = Review::where('review.user_id', $id)
            ->leftJoin('report', 'review.id', '=', 'report.review_id')
            ->groupBy('review.id', 'report.review_id')
            ->select('review.*', 'report.review_id')
            ->get();
        $reports = Report::where('user_id', $id)->get();
        $blocked = false;
        $status = blockAction::where('user_id', $user->id)->orderBy('id', 'desc')->first();
        if ($status != null && $status->blocked_action == 'Blocking'){
            $blocked = true;
        }
        $favourite = Order::where('orders.user_id', $id)
            ->leftJoin('orderedproduct', 'orders.id', '=', 'orderedproduct.order_id')
            ->leftJoin('product', 'orderedproduct.product_id', '=', 'product.id')
            ->groupBy('product.id')
            ->selectRaw('count(product.id) as product_count, product.*')
            ->orderByDesc('product_count')
            ->first();
        $timesblocked = blockAction::where('user_id', $user->id)->where('blocked_action', 'Blocking')->count();
        return view('pages.user_details')->with(['user' => $user, 'orders' => $orders, 'reviews' => $reviews, 'reports' => $reports, 'blocked' => $blocked, 'favourite' => $favourite, 'timesblocked' => $timesblocked]);
    }

    public function adminUsers()
    {  
        if (Auth::user()->is_admin){
            $authenticatedUser = Auth::user();

            $request = request();

            $sortColumn = $request->query('sort_column', 'username');
            $sortDirection = $request->query('sort_direction', 'asc');

            $users = User::where('id', '!=', $authenticatedUser->id)
            ->orderBy($sortColumn, $sortDirection)
            ->paginate(3)
            ->withQueryString();

            $blocked = [];
            foreach ($users as $user){
                $status = blockAction::where('user_id', $user->id)->orderBy('id', 'desc')->first();
                if ($status != null && $status->blocked_action == 'Blocking'){
                    $blocked[] = $status->user_id;
                }
            }

            return view('pages.admin_users')->with([
                'users' => $users,
                'blocked' => $blocked,
            ]);
        } 
        else{
            return redirect()->back();
        }
    }

    public function adminProducts(){
        if (Auth::user()->is_admin){

            $request = request();

            $sortColumn = $request->query('sort_column', 'product_name');
            $sortDirection = $request->query('sort_direction', 'asc');

            $products = Product::join('brand', 'product.brand_id', '=', 'brand.id')
            ->select('product.*', 'brand.brand_name as brand_name')
            ->orderBy($sortColumn, $sortDirection)
            ->paginate(10)
            ->withQueryString();

            $max_id = Product::all()->max('id') + 1;
            $categories = Category::all();
            $brands = Brand::all();
            $photos = Storage::files('public/products');

            return view('pages.admin_products')->with([
                'products' => $products,
                'categories' => $categories,
                'brands' => $brands,
                'max_id' => $max_id,
                'photos' => $photos,
            ]);
        } 
        else{
            return redirect()->back();
        }
    }

    public function adminOrders(){


        if (Auth::user()->is_admin){

            $request = request();

            $sortColumn = $request->query('sort_column', 'order_date');
            $sortDirection = $request->query('sort_direction', 'desc');

            $orders = Order::join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.username as username')
            ->orderBy($sortColumn, $sortDirection)
            ->paginate(3)
            ->withQueryString();

            return view('pages.admin_orders')->with([
                'orders' => $orders
            ]);
        } 
        else{
            return redirect()->back();
        }
    }

    public function adminPromotions(){
        if (Auth::user()->is_admin){

            $request = request();

            $sortColumn = $request->query('sort_column', 'end_date');
            $sortDirection = $request->query('sort_direction', 'asc');

            $promotions = Discount::orderBy($sortColumn, $sortDirection)
            ->paginate(3)
            ->withQueryString();


            $max_id = Discount::all()->max('id') + 1;
            $products = Product::all();

            return view('pages.admin_promotions')->with([
                'promotions' => $promotions,
                'products' => $products,
                'max_id' => $max_id,
            ]);
        } 
        else{
            return redirect()->back();
        }
    }

    public function adminReviews(){
        if (Auth::user()->is_admin){

            $request = request();

            $sortColumn = $request->query('sort_column', 'report_date');
            $sortDirection = $request->query('sort_direction', 'asc');

            $reports = Report::join('users', 'report.user_id', '=', 'users.id')
            ->select('report.*', 'users.username as username')
            ->orderBy($sortColumn, $sortDirection)
            ->paginate(3)
            ->withQueryString();

            return view('pages.admin_reviews')->with([
                'reports' => $reports,
            ]);
        } 
        else{
            return redirect()->back();
        }
    }

    public function statistics(){
        $favourites = Order::leftJoin('orderedproduct', 'orders.id', '=', 'orderedproduct.order_id')
            ->leftJoin('product', 'orderedproduct.product_id', '=', 'product.id')
            ->groupBy('product.id')
            ->selectRaw('count(product.id) as product_count, product.*')
            ->orderByDesc('product_count')
            ->limit(5)
            ->get();
        $topCustomers = User::leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->groupBy('users.id')
            ->selectRaw('count(orders.id) as order_count, users.*')
            ->orderByDesc('order_count')
            ->limit(5)
            ->get();
        return view('pages.statistics')->with([
            'topCustomers' => $topCustomers,
            'favourites' => $favourites,
        ]);
    }
    
    /**
     * Updates user's Username.
     */
    public function edit(Request $request)
    {   
        $this->authorize('edit', User::Class);

        $user = Auth::user();

        $request->validate([
            'username' => 'unique:users,username,'.$user->id.'|max:255',
            'email' => 'email|unique:users,email,'.$user->id.'|max:255',
            'date_of_birth' => 'date|before_or_equal:today|date_format:Y-m-d',
            'password' => 'min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*\W).+$/',
            'phone_number' => 'max:15|regex:/^[0-9\-]+$/'
        ]);

        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->date_of_birth = $request->input('date_of_birth');
        $user->phone_number = $request->input('phone_number');
        $user->save();
        return redirect()->route('user')->with('success', 'Profile updated successfully');
    }

    public function editPassword(Request $request)
    {   
        $user = Auth::user();

        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);
        
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return redirect()->route('user')->with('success', 'Profile updated successfully');
    }

    /**
     * Updates user's Photo.
     */
    public function editPhoto(Request $request)
    {   
        $user = Auth::user();

        $request->validate([
            'photo' => 'required',
        ]);
        Storage::putFileAs('public/images', $request->file('photo'), Auth::user()->id . '.png');
        $user->user_path = $user->id;
        $user->save();
        return redirect()->route('user')->with('success', 'Profile updated successfully');
    }

    /**
     * Block an user.
     */
    public function blockUser(Request $request)
    {
        $this->authorize('blockUser', User::class);
        // Find the user.
        $id = User::where('username', $request->input('username'))->value('id');

        // Check if the current user is authorized to block this user.
        if (!Auth::user()->is_admin){
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $currentDateTime = date('Y-m-d');
        
        blockAction::create([
            'block_date' => $currentDateTime,
            'blocked_action' => $request->input('action'),
            'user_id' => $id
        ]);

        return response()->json(['message' => 'Action done successfully']);
    }

    /**
     * Deletes an user.
     */
    public function deleteUser(Request $request)
    {   
        $this->authorize('deleteUser', User::class);

        $id = User::where('username', $request->input('username'))->value('id');

        DB::beginTransaction();

        DB::statement('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');

        User::where('id', $id)->update(['username' => (string)$id, 'password' => 'none', 'email' => (string)$id, 'user_path' => 'img/default.png', 'is_deleted' => true]);

        Notification::where('user_id', $id)->delete();

        DB::commit();

        return response()->json(['message' => 'Action done successfully']);
    }

    public function readNotifications($id) {
        $user = Auth::user();
        
        // Retrieve the notification
        $notification = Notification::where('user_id', $user->id)
            ->where('id', $id)
            ->first();
    
        if ($notification) {
            // Check if the notification is not already read
            if (!$notification->is_read) {
                // Update 'is_read' to true only if it's currently false
                $notification->update(['is_read' => true]);
                return response()->json(['success' => true, 'notification_id' => $id]);
            } else {
                return response()->json(['success' => false, 'message' => 'Notification is already read']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Notification not found']);
        }
    }
}
