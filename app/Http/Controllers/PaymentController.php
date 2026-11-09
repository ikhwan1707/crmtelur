<?php

namespace App\Http\Controllers;

use App\Order;
use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, Order $order)
    {
        // 1. Validasi dasar
        $request->validate([
            'tgl_bayar' => 'required|date',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // 2. Cek apakah nominal melebihi sisa piutang
        $sisa = $order->sisaPiutang();

        if ($request->jumlah > $sisa) {
            return back()->withErrors([
                'jumlah' => 'Gagal! Nominal pembayaran (Rp ' . number_format($request->jumlah, 0, ',', '.') . ') melebihi sisa piutang (Rp ' . number_format($sisa, 0, ',', '.') . ').'
            ])->withInput();
        }

        // 3. Simpan pembayaran
        $order->payments()->create([
            'tgl_bayar' => $request->tgl_bayar,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Pembayaran berhasil ditambahkan.');
    }
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'Pembayaran dihapus.');
    }
}