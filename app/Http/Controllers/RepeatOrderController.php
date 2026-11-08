<?php

namespace App\Http\Controllers;

use App\RepeatOrder;
use Illuminate\Http\Request;

class RepeatOrderController extends Controller
{
    public function index(Request $request)
    {
        // Tambahkan with('customer.followups') untuk mengambil riwayat followup
        $q = RepeatOrder::with(['customer', 'product', 'customer.followups' => function ($query) {
            $query->latest('tgl_followup')->limit(1); // Ambil 1 followup terakhir
        }])->latest();

        if ($request->status) {
            $q->where('status', $request->status);
        }
        $repeats = $q->paginate(15);
        return view('repeat.index', compact('repeats'));
    }
}