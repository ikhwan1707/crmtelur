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
        $orders = Order::with(['customer','payments'])->latest()->paginate(10);
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
            'tgl_jatuh_tempo' => 'nullable|date|after_or_equal:tgl_order',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|numeric|min:1',
        ]);

        $order = DB::transaction(function () use ($data, $request) {
            $total = 0;

            $itemsData = [];
            
            foreach ($request->items as $it) {
                $product = \App\Product::findOrFail($it['product_id']);
                $hargaAsli = $product->harga;

                $subtotal = $it['jumlah'] * $hargaAsli;
                $total += $subtotal;

                $itemsData[] = [
                    'product_id' => $it['product_id'],
                    'jumlah' => $it['jumlah'],
                    'harga_satuan' => $hargaAsli, // Gunakan harga dari database
                    'subtotal' => $subtotal,
                ];
            }

            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'user_id' => auth()->id(),
                'tgl_order' => $data['tgl_order'],
                'tgl_kirim' => $data['tgl_kirim'] ?? null,
                'status' => $data['status'],
                'metode_bayar' => $data['metode_bayar'],
                'tgl_jatuh_tempo' => $data['tgl_jatuh_tempo'] ?? null,
                'total' => $total,
            ]);

            // Simpan Item Pesanan
            $order->items()->createMany($itemsData);

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
            'tgl_jatuh_tempo' => 'nullable|date|after_or_equal:' . $order->tgl_order->format('Y-m-d'),
        ]);

        if ($data['metode_bayar'] === 'tunai') {
            $data['tgl_jatuh_tempo'] = null;
        }
        
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