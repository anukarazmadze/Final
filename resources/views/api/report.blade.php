@extends('layouts.admin')

@section('title', 'Monthly Statistics -')



@section('content')

<div class="container">
    <h1>Monthly Statistics - {{ $data ['currentMonth']  }}</h1>

    <div class="row adminindex">
        <!-- Total Revenue -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Revenue</h5>
                    <p>${{ number_format($data['totalRevenue'] / 100, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Orders</h5>
                    <p>{{ $data['totalOrders'] }}</p>
                </div>
            </div>
        </div>

         <!-- Total Products -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Products</h5>
                    <p>{{ $data['totalProducts'] }}</p>
                </div>
            </div>
        </div>

        <!-- Order Status Breakdown -->
        <div class="col-md-6 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5>Order Status Breakdown</h5>
                    <ul>
                        @foreach( $data['statusCounts'] as $status => $count)
                        <li>{{ ucfirst($status) }}: {{ $count }}</li>
                        @endforeach
                    </ul>

                </div>

                
            </div>
        </div>

        <!-- ChartScript -->
    </div>

</div>

</div>
@endsection