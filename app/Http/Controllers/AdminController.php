<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\ReportServiceInterface;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    protected $reportService;

    public function __construct(ReportServiceInterface $reportService)
    {
        $this->reportService = $reportService;

        

    }

    public function index()
    {
        $reportData = $this->reportService->index();
        return view('admin.index', $reportData);
    }

    public function productIndex()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'discount' => 'nullable|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'default_photo' => 'nullable|exists:product_images,id',
        ]);

        $product = Product::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'] * 100,
            'category_id' => $validated['category_id'],
            'discount' => $validated['discount'],
            'photo' => '',
        ]);

        if ($request->hasFile('images')) {
        $imagePaths = [];
        $first = true;

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('product-photos', 'public');
            $imagePaths[$index] = $path;

            $isDefault = ($request->input('default_photo') == $index) || ($first && !$request->has('default_photo'));

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_default' => $isDefault,
            ]);

            if ($isDefault) {
                $product->update(['photo' => $path]);
            }

            $first = false;
        }
    }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function editProduct(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'discount' => 'nullable|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'default_photo' => 'nullable|exists:product_images,id',
        ]);

        $product->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'] * 100,
            'category_id' => $validated['category_id'],
            'discount' => $validated['discount'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product-photos', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        if ($validated['default_photo']) {
            // Reset other images' default status
            ProductImage::where('product_id', $product->id)->update(['is_default' => false]);
    
            // Set the new default image
            $productImage = ProductImage::find($validated['default_photo']);
            $productImage->update(['is_default' => true]);
    
            // Update product's main photo
            $product->update(['photo' => $productImage->image_path]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroyProduct(Product $product)
    {
        DB::transaction(function () use ($product) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
    
            $product->delete();
        });

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}