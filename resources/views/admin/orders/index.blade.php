@extends('layouts.adminPage')

@section('title', 'Orders -')

@section('content')
<div class="container mt-4">
    <h3>Orders</h3>

    <!-- Display Success Message -->
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-3">
            <form method="GET" action="{{ route('admin.orders.index') }}">
                <select id="order-status-filter" name="status" class="form-select" aria-label="Order Status Filter" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Orders</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                    <option value="stale" {{ request('status') == 'stale' ? 'selected' : '' }}>Stale</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Finished</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </form>
        </div>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Client Name</th>
                <th scope="col">Total Price</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <th scope="row">{{ $order->id }}</th>
                <td><a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->user->name }}</a></td>
                <td>${{ number_format($order->total_price / 100, 2) }}</td>
                <td>
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="d-flex align-items-center">
                        @csrf
                        <select name="status" class="form-select form-select1 me-2" aria-label="Order Status">
                            <option value="new" {{ $order->status == 'new' ? 'selected' : '' }}>New</option>
                            <option value="stale" {{ $order->status == 'stale' ? 'selected' : '' }}>Stale</option>
                            <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="finished" {{ $order->status == 'finished' ? 'selected' : '' }}>Finished</option>
                            <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Change</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $orders->links() }}
</div>
@endsection
