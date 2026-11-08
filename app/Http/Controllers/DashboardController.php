<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use App\RepeatOrder;
use Carbon\Carbon; // Tambahkan Carbon untuk manipulasi tanggal

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ringkasan Data
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();
        $perluFollowup = RepeatOrder::where('status', 'terlambat')->count();

        $piutang = Order::where('metode_bayar', 'tempo')->get()->sum(function ($o) {
            return $o->sisaPiutang();
        });

        // 2. Data Grafik Pendapatan (7 Hari Terakhir)
        $chartDates = [];
        $chartRevenues = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartDates[] = $date->translatedFormat('d M'); // Format: 08 Nov

            // Ambil total pendapatan per hari (abaikan yang batal)
            $revenue = Order::whereDate('tgl_order', $date)
                ->where('status', '!=', 'batal')
                ->sum('total');
            $chartRevenues[] = $revenue;
        }

        return view('dashboard', compact(
            'totalCustomers',
            'totalOrders',
            'perluFollowup',
            'piutang',
            'chartDates',
            'chartRevenues'
        ));
    }
}