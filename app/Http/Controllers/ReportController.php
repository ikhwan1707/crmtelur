<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Data Pelanggan Teratas
        $topCustomers = Customer::addSelect([
            'total_belanja' => Order::select(DB::raw('sum(total)'))
                ->whereColumn('customer_id', 'customers.id')
                ->where('status', '!=', 'batal') // Abaikan yang batal
        ])
            ->orderByDesc('total_belanja')
            ->take(5)
            ->get();

        // 2. Data Pelanggan Tidak Aktif (> 30 hari)
        $inactive = Customer::whereDoesntHave('orders', function ($q) {
            $q->where('tgl_order', '>=', now()->subDays(30));
        })->take(10)->get();

        // 3. Data Piutang Belum Lunas
        $piutang = Order::where('metode_bayar', 'tempo')
            ->with('customer', 'payments')
            ->get()
            ->filter(function ($o) {
                return $o->sisaPiutang() > 0;
            });

        // 4. Ringkasan (Summary)
        $totalPendapatan = Order::where('status', 'selesai')->sum('total');
        $totalPiutang = $piutang->sum(function ($o) {
            return $o->sisaPiutang();
        });
        $jumlahTidakAktif = $inactive->count();

        return view('reports.index', compact(
            'topCustomers',
            'inactive',
            'piutang',
            'totalPendapatan',
            'totalPiutang',
            'jumlahTidakAktif'
        ));
    }
}