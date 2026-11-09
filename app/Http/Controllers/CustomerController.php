<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }
    public function create()
    {
        return view('customers.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'kategori' => 'required|in:eceran,warung,reseller,katering',
            'no_wa' => 'required|string|max:20',
            'alamat' => 'required|string',
            'status' => 'required|in:aktif,pasif',
            'tgl_daftar' => 'required|date',
        ]);
        Customer::create($data);
        return redirect()->route('customers.index')->with('success', 'Pelanggan ditambahkan.');
    }
    public function show(Customer $customer)
    {
        $customer->load('orders.items.product', 'orders.payments', 'followups');

        // Hitung Ringkasan Keuangan Pelanggan
        $totalBelanja = $customer->orders->sum('total');
        $totalDibayar = $customer->orders->sum(function ($order) {
            return $order->totalDibayar();
        });
        $sisaHutang = $totalBelanja - $totalDibayar;

        // Hitung Hutang yang sudah jatuh tempo
        $hutangJatuhTempo = $customer->orders->filter(function ($order) {
            return $order->sisaPiutang() > 0 && $order->tgl_jatuh_tempo && $order->tgl_jatuh_tempo->isPast();
        })->sum(function ($order) {
            return $order->sisaPiutang();
        });

        return view('customers.show', compact(
            'customer',
            'totalBelanja',
            'totalDibayar',
            'sisaHutang',
            'hutangJatuhTempo'
        ));
    }
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }
    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'kategori' => 'required|in:eceran,warung,reseller,katering',
            'no_wa' => 'required|string|max:20',
            'alamat' => 'required|string',
            'status' => 'required|in:aktif,pasif',
            'tgl_daftar' => 'required|date',
        ]);
        $customer->update($data);
        return redirect()->route('customers.index')->with('success', 'Pelanggan diperbarui.');
    }
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Pelanggan dihapus.');
    }
}