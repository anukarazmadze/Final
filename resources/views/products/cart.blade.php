@extends('layouts.admin')

@section('title', 'My Cart')

@section('content')
<div class="container">
    <h1>Your Cart</h1>

    @foreach($cartItems as $item)
    <div class="d-flex align-items-center mb-3">
        <div class="card flex-grow-1 position-relative" id="cart-item-{{ $item->id }}">
            <div class="row g-0 align-items-center">
                <!-- Checkbox on the left side, vertically centered -->
                <div class="col-auto">
                    <input type="checkbox" class="cart-item-checkbox" onchange="updateSelectedTotal()" checked>
                </div>

                <!-- Product Image -->
                <div class="col-md-4 photo2">
                    <img src="{{ asset('storage/' . $item->product->photo) }}" class="img-fluid rounded-start" alt="{{ $item->product->title }}">
                </div>

                <!-- Product Details -->
                <div class="col-md-8 d-flex flex-column justify-content-between">
                    <div class="card-body">
                        <!-- Form for removing the cart item -->
                        <form action="{{ route('products.cart.remove', $item->id) }}" method="POST" class="position-absolute" style="top: 10px; right: 15px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-close" aria-label="Close"></button>
                        </form>

                        <!-- Product Title -->
                        <h5 class="card-title card-title2">{{ $item->product->title }}</h5>

                        <!-- Price and Quantity Controls -->
                        <div class="d-flex justify-content-between align-items-center price2">
                            @if($item->product->discount)
                                <p class="card-text mb-0">
                                    Price: 
                                    <span class="text-muted"><del>${{ number_format($item->product->price / 100, 2) }}</del></span>
                                    ${{ $item->product->discounted_price }}
                                </p>                                
                            @else
                                <p class="card-text mb-0">Price: ${{ number_format($item->product->price / 100, 2) }}</p>                                
                            @endif

                            <!-- Quantity Controls -->
                            <div class="quantity-selector">
                                <form action="{{ route('products.cart.update', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" name="quantity" value="{{ $item->quantity - 1 }}" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                    <span>{{ $item->quantity }}</span>
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" name="quantity" value="{{ $item->quantity + 1 }}">+</button>
                                </form>
                            </div>
                        </div>

                        <!-- Total Price at the bottom -->
                         @if($item->product->discount)
                                
                                <p class="card-text card-text2 ">
                                    Total: ${{ number_format(($item->product->discounted_price * $item->quantity), 2) }}
                                </p>
                            @else
                                <p class="card-text card-text2 mt-2">
                                    Total: ${{ number_format(($item->product->price * $item->quantity) / 100, 2) }}
                                </p>
                            @endif
                        {{-- <p class="card-text card-text2 mt-2">Total: $<span id="item-total-{{ $item->id }}">{{ number_format(($item->price * $item->quantity) / 100, 2) }}</span></p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Cart Total and Place Order Button -->
    <h3 id="cart-total" class="mt-4">Total: ${{ session('totalPrice') ?? number_format($totalPrice / 100, 2) }}</h3>
    <a href="{{ route('orders.create') }}" class="btn btn2 mt-2">Place Order</a>
</div>
@endsection
