@extends('layouts.app')
@section('content')
<h3>Pesanan #{{ $order->id }}</h3>
<p>Pelanggan: {{ $order->customer->nama }}<br>
    Tanggal: {{ $order->tgl_order->format('d-m-Y') }}<br>
    Status: {{ $order->status }} | Bayar: {{ $order->metode_bayar }}</p>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $it)
        <tr>
            <td>{{ $it->product->nama }}</td>
            <td>{{ $it->jumlah }}</td>
            <td>Rp {{ number_format($it->harga_satuan, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($it->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">Total</th>
            <th>Rp {{ number_format($order->total, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

<h5>Pembayaran</h5>
<table class="table table-sm">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->payments as $p)
        <tr>
            <td>{{ $p->tgl_bayar->format('d-m-Y') }}</td>
            <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
            <td>{{ $p->keterangan }}</td>
            <td>
                <form action="{{ route('payments.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<p>Total dibayar: Rp {{ number_format($order->totalDibayar(), 0, ',', '.') }}<br>
    Sisa piutang: Rp {{ number_format($order->sisaPiutang(), 0, ',', '.') }}</p>

<form action="{{ route('payments.store', $order) }}" method="POST" class="form-inline">
    @csrf
    <input type="date" name="tgl_bayar" class="form-control mr-2" value="{{ now()->format('Y-m-d') }}" required>
    <input type="number" step="0.01" name="jumlah" class="form-control mr-2" placeholder="Jumlah" required>
    <input type="text" name="keterangan" class="form-control mr-2" placeholder="Keterangan">
    <button class="btn btn-primary">Tambah Pembayaran</button>
</form>

<div class="mt-3">
    <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection