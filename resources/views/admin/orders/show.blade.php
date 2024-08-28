@extends('layouts.adminPage')

@section('title', 'Order -')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Order #{{ $order->id }}</h3>

    <!-- Client Information -->
    <div class="card mb-4">
        <div class="card-header btn2 text-dark">
            Client Information
        </div>
        <div class="card-body">
            <form>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Name:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $order->user->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-3 col-form-label">Email:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $order->user->email }}" readonly>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-3 col-form-label">Phone:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $order->user->phone_number }}" readonly>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Order Details -->
    <div class="card mb-4">
        <div class="card-header btn2 text-dark">
            Order Details
        </div>
        <div class="card-body">
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Total Price:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="${{ number_format($order->total_price / 100, 2) }}" readonly>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-sm-3 col-form-label">Status:</label>
                    <div class="col-sm-9">
                        <select name="status" class="form-control">
                           <option value="new" {{ $order->status == 'new' ? 'selected' : '' }}>New</option>
                            <option value="stale" {{ $order->status == 'stale' ? 'selected' : '' }}>Stale</option>
                            <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="finished" {{ $order->status == 'finished' ? 'selected' : '' }}>Finished</option>
                            <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary btn2">Update Status</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products List -->
    <div class="card mb-4">
        <div class="card-header btn2 text-dark">
            Products
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($order->products as $product)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $product->title }} 
                        <span>{{ $product->pivot->quantity }} x ${{ number_format($product->price / 100, 2) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Back to Orders Button -->
    <div class="text-right">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn2">Back to Orders</a>
    </div>
</div>
@endsection
