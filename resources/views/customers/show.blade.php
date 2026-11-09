@extends('layouts.app')
@section('content')
<h3>{{ $customer->nama }}</h3>
<p>Kategori: {{ $customer->kategori }} | No WA: {{ $customer->no_wa }} | Status: {{ $customer->status }}</p>
<p>Alamat: {{ $customer->alamat }}</p>

<div class="row mb-4 mt-4">
    <div class="col-md-3">
        <div class="card bg-light border-0 shadow-sm p-3">
            <small class="text-muted font-weight-bold">Total Belanja</small>
            <h4 class="mb-0 text-primary">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light border-0 shadow-sm p-3">
            <small class="text-muted font-weight-bold">Total Telah Dibayar</small>
            <h4 class="mb-0 text-success">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light border-0 shadow-sm p-3">
            <small class="text-muted font-weight-bold">Sisa Hutang</small>
            <h4 class="mb-0 {{ $sisaHutang > 0 ? 'text-danger' : 'text-success' }}">
                Rp {{ number_format($sisaHutang, 0, ',', '.') }}
            </h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 {{ $hutangJatuhTempo > 0 ? 'bg-danger text-white' : 'bg-light' }}">
            <small class="{{ $hutangJatuhTempo > 0 ? 'text-white-50' : 'text-muted' }} font-weight-bold">Hutang Jatuh
                Tempo</small>
            <h4 class="mb-0">Rp {{ number_format($hutangJatuhTempo, 0, ',', '.') }}</h4>
        </div>
    </div>
</div>

<h5 class="mt-4">Riwayat Pesanan</h5>
<table class="table table-sm table-bordered">
    <thead class="bg-light">
        <tr>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status Pesanan</th>
            <th>Pembayaran</th> {{-- KOLOM BARU --}}
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($customer->orders->sortByDesc('tgl_order') as $o)
        <tr>
            <td>{{ $o->tgl_order->format('d-m-Y') }}</td>
            <td>Rp {{ number_format($o->total, 0, ',', '.') }}</td>
            <td>
                <span class="badge badge-secondary">{{ $o->status }}</span>
            </td>
            <td>
                {{-- LOGIKA HUTANG / LUNAS --}}
                @php
                $sisa = $o->sisaPiutang();
                @endphp

                @if($sisa <= 0) <span class="badge badge-success">Lunas</span>
                    @else
                    <span class="badge badge-danger">Hutang: Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                    @endif
            </td>
            <td>
                <a href="{{ route('orders.show', $o) }}" class="btn btn-sm btn-info">Detail</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center text-muted py-3">Belum ada riwayat pesanan</td>
        </tr>
        @endforelse
    </tbody>
</table>

<h5 class="mt-4">Riwayat Follow-up</h5>
<ul>
    @foreach($customer->followups as $f)
    <li>{{ $f->tgl_followup->format('d-m-Y') }} — {{ $f->jenis }} — {{ $f->hasil }} — {{ $f->catatan }}</li>
    @endforeach
</ul>
<a href="{{ route('followups.create', $customer) }}" class="btn btn-primary btn-sm">Follow-up Baru</a>
@endsection