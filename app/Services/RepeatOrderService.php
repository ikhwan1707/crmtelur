<?php

namespace App\Services;

use App\Order;
use App\RepeatOrder;

class RepeatOrderService
{
    /**
     * Hitung ulang pola pesanan berulang untuk semua produk
     * yang ada pada order ini.
     */
    public function recalculateForOrder(Order $order)
    {
        $productIds = $order->items()->pluck('product_id')->unique();
        foreach ($productIds as $pid) {
            $this->recalculate($order->customer_id, $pid);
        }
    }

    public function recalculate($customerId, $productId)
    {
        // Ambil semua order valid (bukan batal) milik pelanggan yang mengandung produk ini
        $orders = Order::where('customer_id', $customerId)
            ->where('status', '!=', 'batal')
            ->whereHas('items', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->orderBy('tgl_order')
            ->get();

        // Pola hanya dihitung jika minimal 3 pesanan
        if ($orders->count() < 3) {
            RepeatOrder::where('customer_id', $customerId)
                ->where('product_id', $productId)
                ->delete();
            return;
        }

        // Hitung rata-rata interval
        $intervals = [];
        for ($i = 1; $i < $orders->count(); $i++) {
            $intervals[] = abs($orders[$i]->tgl_order->diffInDays($orders[$i - 1]->tgl_order));
        }
        $avgInterval = round(array_sum($intervals) / count($intervals));

        // Hitung rata-rata jumlah
        $jumlahs = $orders->map(function ($o) use ($productId) {
            return $o->items()->where('product_id', $productId)->sum('jumlah');
        });
        $avgJumlah = round($jumlahs->avg(), 2);

        $lastOrder = $orders->last()->tgl_order;
        $nextOrder = $lastOrder->copy()->addDays($avgInterval);

        $status = 'aktif';
        if ($nextOrder->lt(now()->startOfDay())) {
            $status = 'terlambat';
        }

        RepeatOrder::updateOrCreate(
            ['customer_id' => $customerId, 'product_id' => $productId],
            [
                'interval_hari' => $avgInterval,
                'jumlah_rata2' => $avgJumlah,
                'last_order_date' => $lastOrder,
                'next_order_date' => $nextOrder,
                'status' => $status,
            ]
        );
    }
}