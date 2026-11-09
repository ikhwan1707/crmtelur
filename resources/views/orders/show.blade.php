@extends('layouts.app')
@section('content')
<h3>Pesanan #{{ $order->id }}</h3>
<div class="mb-4">
    <p class="mb-1">
        <strong>Pelanggan:</strong> <a href="{{ route('customers.show', $order->customer_id) }}">{{
            $order->customer->nama }}</a><br>
        <strong>Tanggal Order:</strong> {{ $order->tgl_order->format('d-m-Y') }}<br>
        <strong>Status Pesanan:</strong> <span class="badge badge-secondary">{{ $order->status }}</span> |
        <strong>Metode Bayar:</strong> {{ ucfirst($order->metode_bayar) }}
    </p>

    {{-- LOGIKA JATUH TEMPO --}}
    {{-- TAMBAHKAN KONDISI: && $order->sisaPiutang() > 0 --}}
    @if($order->metode_bayar == 'tempo' && $order->sisaPiutang() > 0)
    @php
    $isJatuhTempo = $order->tgl_jatuh_tempo && $order->tgl_jatuh_tempo->isPast();
    @endphp
    
    @if($order->tgl_jatuh_tempo)
    <div class="alert {{ $isJatuhTempo ? 'alert-danger' : 'alert-warning' }} py-2 d-inline-block">
        <i class="fas fa-clock"></i>
        <strong>Jatuh Tempo:</strong> {{ $order->tgl_jatuh_tempo->format('d-m-Y') }}
    
        @if($isJatuhTempo)
        <span class="font-weight-bold">(TERLAMBAT!)</span>
        @endif
    </div>
    @else
    <div class="alert alert-secondary py-2 d-inline-block">
        <i class="fas fa-info-circle"></i> Tanggal jatuh tempo belum diatur.
    </div>
    @endif
    @endif
</div>

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

<h5 class="mt-4">Pembayaran</h5>
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
        @forelse($order->payments as $p)
        <tr>
            <td>{{ $p->tgl_bayar->format('d-m-Y') }}</td>
            <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
            <td>{{ $p->keterangan }}</td>
            <td>
               @if(auth()->user()->role === 'admin')
                <form action="{{ route('payments.destroy', $p) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan pembayaran ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
                @else
                <span class="text-muted" style="font-size: 0.8rem;">-</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-muted text-center">Belum ada pembayaran</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="mb-3">
    <strong>Total dibayar:</strong> Rp {{ number_format($order->totalDibayar(), 0, ',', '.') }}<br>
    <strong>Sisa piutang:</strong>
    <span class="{{ $order->sisaPiutang() > 0 ? 'text-danger' : 'text-success' }}">
        Rp {{ number_format($order->sisaPiutang(), 0, ',', '.') }}
    </span>
</div>

{{-- LOGIKA BARU: Hanya tampilkan form jika masih ada sisa piutang --}}
@if($order->sisaPiutang() > 0)
<form action="{{ route('payments.store', $order) }}" method="POST" class="form-inline p-3 bg-light border rounded">
    @csrf
    <input type="date" name="tgl_bayar" class="form-control mr-2" value="{{ now()->format('Y-m-d') }}" required>

    {{-- TAMBAHKAN ATRIBUT max DI SINI --}}
    <input type="number" step="0.01" name="jumlah" class="form-control mr-2" placeholder="Jumlah" required
        max="{{ $order->sisaPiutang() }}" min="1">

    <input type="text" name="keterangan" class="form-control mr-2" placeholder="Keterangan">
    <button class="btn btn-primary">Tambah Pembayaran</button>
</form>
<small class="text-muted mt-1 d-block">Maksimal pembayaran yang bisa diinput: Rp {{ number_format($order->sisaPiutang(),
    0, ',', '.') }}</small>
@else
<div class="alert alert-success py-2">
    <i class="fas fa-check-circle"></i> Pesanan ini sudah <strong>Lunas</strong>.
</div>
@endif

<div class="mt-3">
    <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection