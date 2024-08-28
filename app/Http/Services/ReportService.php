<?php

namespace App\Http\Services;

use App\Http\Interfaces\ReportServiceInterface;
use App\Models\Order;
use App\Models\Product;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ReportService implements ReportServiceInterface {

    public function index()
    {
        $data = Cache::remember('admin_index_data', now()->addSeconds(1), function () {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $orders = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->get();

            $totalRevenue = $orders->sum('total_price');
            $totalOrders = $orders->count();
            $statusCounts = $orders->groupBy('status')->map->count();

    $totalProducts = Product::count();

            return [
                'totalRevenue' => $totalRevenue,
                'totalOrders' => $totalOrders,
                'statusCounts' => $statusCounts,
                'currentMonth' => $startOfMonth->format('F Y'), // Format current month as "Month Year"
                'totalProducts' => $totalProducts, // Add this line

            ];
        });

        $chart = (new LarapexChart)
            ->pieChart()
            ->setTitle('Order Status Distribution')
            ->setColors(['#f8b400', '#008ffb', '#00e396', '#ff4560', '#775dd0'])  // Custom colors for different statuses
            ->setDataset($data['statusCounts']->values()->toArray())  // Values for each status
            ->setLabels($data['statusCounts']->keys()->map(fn($status) => ucfirst($status))->toArray());  // Labels for each status

        return compact('data', 'chart');
    }


}

?>