<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $categories = Category::all();
    $selectedCategory = $request->get('category_id');
    $showFeatured = $request->get('show') === 'featured';

    $query = Product::query();

    if ($selectedCategory && !$showFeatured) {
        $query->where('category_id', $selectedCategory);
    }

    if ($showFeatured) {
        // Fetch featured products
        $products = Product::where('created_at', '>=', now()->subDays(10))
            ->orderBy('id', 'desc')
            ->get();
    } else {
        $products = $query->get();
    }

    $featuredProducts = Product::where('created_at', '>=', now()->subDays(10))
        ->orderBy('id', 'desc')
        ->get();

    return view('products.index', compact('products', 'categories', 'selectedCategory', 'showFeatured', 'featuredProducts'));
}


    
}
