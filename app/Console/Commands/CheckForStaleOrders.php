<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckForStaleOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-for-stale-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark orders as stale if they are older than 2 days and still new';



    /**
     * Execute the console command.
     */
    public function handle()
    {
        $staleOrders = Order::where('status', 'new')
            ->where('created_at', '<=', Carbon::now()->subDays(2))
            ->update(['status' => 'stale']);

        $this->info($staleOrders . ' orders have been marked as stale.');
    }
}
