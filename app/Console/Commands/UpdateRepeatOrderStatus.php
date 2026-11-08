<?php

namespace App\Console\Commands;

use App\RepeatOrder;
use Illuminate\Console\Command;

class UpdateRepeatOrderStatus extends Command
{
    protected $signature = 'repeat:update-status';
    protected $description = 'Update status repeat order menjadi terlambat jika melewati next_order_date';

    public function handle()
    {
        RepeatOrder::where('status', 'aktif')
            ->whereNotNull('next_order_date')
            ->where('next_order_date', '<', now()->startOfDay())
            ->update(['status' => 'terlambat']);

        $this->info('Status repeat order diperbarui.');
    }
}