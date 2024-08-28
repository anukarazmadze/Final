<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Display a listing of the orders
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
    
    // Define the number of orders per page

    if ($status == 'all') {
        // Fetch all orders with pagination
        $orders = Order::paginate(60);
    } else {
        // Fetch orders based on the status with pagination
        $orders = Order::where('status', $status)->paginate(60);
    }

        return view('admin.orders.index', compact('orders'));
    }

    // Show the form for creating a new order
    public function create()
    {
        $cartItems = CartProduct::where('user_id', Auth::id())->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('products.cart')->with('error', 'Your cart is empty!');
    }

    $totalPrice = $cartItems->sum(function ($item) {
        return $item->price * $item->quantity;
    });

    $order = Order::create([
        'user_id' => Auth::id(),
        'total_price' => $totalPrice,
        'status' => 'new',
    ]);

    // Attach cart items to the order
    foreach ($cartItems as $item) {
        $order->products()->attach($item->product_id, [
            'quantity' => $item->quantity,
            'price' => $item->price,
        ]);

        $item->delete();
    }

    return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    // Store a newly created order in storage
    public function store(Request $request)
    {
        $cartItems = CartProduct::where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products.cart')->with('error', 'Your cart is empty!');
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $totalPrice,
            'status' => 'new',
        ]);

        foreach ($cartItems as $item) {
            $order->products()->attach($item->product_id, [
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        CartProduct::where('user_id', Auth::id())->delete();

        return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully!');
    }

    // Display the specified order
    public function show($id)
    {
        $order = Order::with('products', 'user')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Update the order status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,accepted,finished,rejected',
        ]);
    
        $order = Order::findOrFail($id);
        $order->status = $request->input('status');
        $order->save();

    return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    public function userOrders(Request $request)
{
    $status = $request->input('status', 'all');

    if ($status == 'all') {
        $orders = Order::where('user_id', Auth::id())->paginate(60);
    } else {
        $orders = Order::where('user_id', Auth::id())
                       ->where('status', $status)
                       ->paginate(60);
    }

    return view('orders.index', compact('orders'));
}

}
