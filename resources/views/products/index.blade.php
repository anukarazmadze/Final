@extends('layouts.admin')

@section('content')
<div class="container">

    

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="row">
        <!-- Side Menu for Categories and Featured Products -->
        <div class="col-md-3">
            <h4>Categories</h4>
            <ul class="list-group">
                <li class="list-group-item {{ is_null($selectedCategory) && !$showFeatured ? 'active' : '' }}">
                    <a href="{{ route('products.index') }}">All Products</a>
                </li>
                @foreach($categories as $category)
                <li class="list-group-item {{ $selectedCategory == $category->id && !$showFeatured ? 'active' : '' }}">
                    <a href="{{ route('products.index', ['category_id' => $category->id]) }}">
                        {{ $category->name }}
                    </a>
                </li>
                @endforeach
            </ul>

            <!-- Featured Products Link -->
            <h4 class="mt-4">Featured Products</h4>
            <ul class="list-group">
                <li class="list-group-item {{ $showFeatured ? 'active' : '' }}">
                    <a href="{{ route('products.index', ['show' => 'featured']) }}">Featured Products</a>
                </li>
            </ul>
        </div>

        <!-- Products Listing -->
        <div class="col-md-9">
            <div class="row">
                <!-- Display Products -->
                @forelse($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <!-- Product Image Carousel -->
                        <div id="productCarousel{{ $product->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($product->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100" alt="{{ $product->title }}">
                                </div>
                                @endforeach
                            </div>

                            @if($product->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel{{ $product->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel{{ $product->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            @if($product->discount)
                                <p class="card-text">
                                    <span class="text-muted"><del>${{ number_format($product->price / 100, 2) }}</del></span>
                                    ${{ $product->discounted_price }}
                                </p>
                            @else
                                <p class="card-text">${{ number_format($product->price / 100, 2) }}</p>
                            @endif
                            @if(Auth::check())
                            <form action="{{ route('products.cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-cart4"></i> Add to Cart
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="bi bi-cart4"></i> Add to Cart
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-md-12">
                    <p>No products available.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

