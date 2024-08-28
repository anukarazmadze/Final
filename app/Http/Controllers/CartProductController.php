<?php

namespace App\Http\Controllers;

use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartProductController extends Controller
{
    public function index()

    {
        $cartItems = CartProduct::where('user_id', Auth::id())
            ->with('product')
            ->get();
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('products.cart', compact('cartItems', 'totalPrice'));
    }

    public function add(Product $product)
    {
        $cartItem = CartProduct::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', 1, ['price' => $product->price]);
        } else {
            CartProduct::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }



        return redirect()->route('products.index')->with('success', 'Producthas been added to cart!');
    }



    public function update(Request $request, CartProduct $cartItem)
    {
        $cartItem->update(['quantity' => $request->quantity]);

        $totalPrice = DB::table('cart_products')
            ->where('user_id', Auth::id())
            ->sum(DB::raw('price * quantity'));

        return redirect()->route('products.cart')
            ->with('success', 'Cart updated!')
            ->with('totalPrice', number_format($totalPrice / 100, 2));
    }



    public function remove(CartProduct $cartItem)
    {
        $cartItem->delete();



        $totalPrice = CartProduct::where('user_id', Auth::id())
            ->get()
            ->sum(function ($item) {
                return $item->price * $item->quantity;
            });


        return redirect()->route('products.cart')->with('success', 'Product removed from cart!')
            ->with('totalPrice', $totalPrice);
    }
}
