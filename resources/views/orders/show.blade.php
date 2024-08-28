@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Order #{{ $order->id }}</h3>
    <p>Total Price: ${{ number_format($order->total_price / 100, 2) }}</p>
    <p>Status: {{ ucfirst($order->status) }}</p>
    <h5>Products</h5>
    <ul>
        @foreach($order->products as $product)
            <li>{{ $product->name }} ({{ $product->pivot->quantity }} x ${{ number_format($product->price / 100, 2) }})</li>
        @endforeach
    </ul>
    <a href="{{ route('orders.create') }}" class="btn btn-secondary">Back to Create Order</a>
</div>
@endsection