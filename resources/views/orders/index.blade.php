@extends('layouts.admin')

@section('title', 'My Orders -')

@section('content')
<div class="container">
    <h2>Your Order History</h2>

    @if($orders->isEmpty())
        <p>You have not placed any orders yet.</p>
    @else
        <div class="row">
            @foreach($orders as $order)
                @foreach($order->products as $product)
                    <div class="col-12 mb-4">
                        <div class="card h-100">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="{{ asset('storage/' . $product->photo) }}" class="card-img" alt="{{ $product->title }}">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->title }}</h5>
                                        <p class="card-text">Price: ${{ number_format($product->pivot->price / 100, 2) }}</p>
                                        <p class="card-text">Quantity: {{ $product->pivot->quantity }}</p>
                                        <p class="card-text">Total: ${{ number_format(($product->pivot->price * $product->pivot->quantity) / 100, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
        {{ $orders->links() }} <!-- Pagination links -->
    @endif
</div>
@endsection
