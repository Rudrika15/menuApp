<?php

namespace App\Console\Commands;

use App\Models\OrderDetail;
use App\Models\OrderMaster;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class DeleteOldOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:old-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete orders older than one month along with their details';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oneMonthAgo = "2024-08-31";
        // $oneMonthAgo = Carbon::now()->tz('Asia/Kolkata')->subMonthNoOverflow()->endOfMonth()->format('Y-m-d H:i:s');
        $oldOrders = OrderMaster::whereDate('created_at', '<=', $oneMonthAgo)->get();
        if ($oldOrders->isNotEmpty()) {
            foreach ($oldOrders as $order) {
                OrderDetail::where('orderId', $order->id)->delete();

                $order->delete();
            }
            $this->info('Old orders have been deleted successfully.');
        } else {
            $this->info('No old orders found to delete.');
        }

    }
}
