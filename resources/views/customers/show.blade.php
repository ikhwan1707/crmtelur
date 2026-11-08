@extends('layouts.app')
@section('content')
<h3>{{ $customer->nama }}</h3>
<p>Kategori: {{ $customer->kategori }} | No WA: {{ $customer->no_wa }} | Status: {{ $customer->status }}</p>
<p>Alamat: {{ $customer->alamat }}</p>

<h5 class="mt-4">Riwayat Pesanan</h5>
<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($customer->orders as $o)
        <tr>
            <td>{{ $o->tgl_order->format('d-m-Y') }}</td>
            <td>Rp {{ number_format($o->total, 0, ',', '.') }}</td>
            <td>{{ $o->status }}</td>
            <td><a href="{{ route('orders.show', $o) }}" class="btn btn-sm btn-info">Detail</a></td>
        </tr>
        @endforeach
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