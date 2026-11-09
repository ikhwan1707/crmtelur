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
            <th>Pembayaran</th>
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
           <td>
            {{-- Status Pesanan (Diproses, Dikirim, dll) --}}
            <span class="badge badge-secondary">{{ $o->status }}</span>
        </td>
        <td>
            @php
            $sisa = $o->sisaPiutang();
            $terlambat = $o->tgl_jatuh_tempo && $o->tgl_jatuh_tempo->isPast() && $sisa > 0;
            @endphp
        
            @if($sisa <= 0) <span class="badge badge-success">Lunas</span>
                @else
                <span class="badge badge-danger">Hutang: Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                @if($terlambat)
                <br><small class="text-danger font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Jatuh Tempo ({{
                    $o->tgl_jatuh_tempo->format('d-m-Y') }})</small>
                @endif
                @endif
        </td>
            <td><a href="{{ route('orders.show', $o) }}" class="btn btn-sm btn-info">Detail</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $orders->links() }}
@endsection