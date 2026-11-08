@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Daftar Pesanan</h3>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">+ Pesanan Baru</a>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Pelanggan</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $o)
        <tr>
            <td>{{ $o->id }}</td>
            <td>{{ $o->customer->nama }}</td>
            <td>{{ $o->tgl_order->format('d-m-Y') }}</td>
            <td>Rp {{ number_format($o->total, 0, ',', '.') }}</td>
            <td>{{ $o->status }}</td>
            <td><a href="{{ route('orders.show', $o) }}" class="btn btn-sm btn-info">Detail</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $orders->links() }}
@endsection