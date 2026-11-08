<?php

namespace App\Http\Controllers;
use App\Customer;
use App\Order;
use App\Product;
use App\Services\RepeatOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }
    public function create()
    {
        return view('orders.create', [
            'customers' => Customer::orderBy('nama')->get(),
            'products'  => Product::orderBy('nama')->get(),
        ]);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'tgl_order' => 'required|date',
            'tgl_kirim' => 'nullable|date',
            'status' => 'required|in:diproses,dikirim,selesai,batal',
            'metode_bayar' => 'required|in:tunai,tempo',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|numeric|min:0.01',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        $order = DB::transaction(function () use ($data, $request) {
            $total = 0;
            foreach ($request->items as $it) {
                $total += $it['jumlah'] * $it['harga_satuan'];
            }
            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'user_id' => auth()->id(),
                'tgl_order' => $data['tgl_order'],
                'tgl_kirim' => $data['tgl_kirim'] ?? null,
                'status' => $data['status'],
                'metode_bayar' => $data['metode_bayar'],
                'total' => $total,
            ]);
            foreach ($request->items as $it) {
                $order->items()->create([
                    'product_id' => $it['product_id'],
                    'jumlah' => $it['jumlah'],
                    'harga_satuan' => $it['harga_satuan'],
                    'subtotal' => $it['jumlah'] * $it['harga_satuan'],
                ]);
            }
            return $order;
        });

        app(RepeatOrderService::class)->recalculateForOrder($order);

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan disimpan.');
    }
    public function show(Order $order)
    {
        $order->load('customer', 'user', 'items.product', 'payments');
        return view('orders.show', compact('order'));
    }
    public function edit(Order $order)
    {
        return view('orders.edit', [
            'order' => $order,
            'customers' => Customer::orderBy('nama')->get(),
        ]);
    }
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:diproses,dikirim,selesai,batal',
            'metode_bayar' => 'required|in:tunai,tempo',
            'tgl_kirim' => 'nullable|date',
        ]);
        $order->update($data);
        app(RepeatOrderService::class)->recalculateForOrder($order);
        return redirect()->route('orders.show', $order)->with('success', 'Pesanan diperbarui.');
    }
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Pesanan dihapus.');
    }
}