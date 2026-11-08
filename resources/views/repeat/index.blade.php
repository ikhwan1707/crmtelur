@extends('layouts.app')
@section('content')
<h3>Pelacakan Pesanan Berulang</h3>
<form class="form-inline mb-3">
    <select name="status" class="form-control mr-2">
        <option value="">Semua Status</option>
        @foreach(['aktif','terlambat','berhenti'] as $s)
        <option value="{{ $s }}" {{ request('status')==$s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>
    <button class="btn btn-secondary">Filter</button>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Pelanggan</th>
            <th>Produk</th>
            <th>Interval</th>
            <th>Prediksi Berikutnya</th>
            <th>Status</th>
            <th>Follow-up Terakhir</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($repeats as $r)
        <tr class="{{ $r->status == 'terlambat' ? 'table-warning' : '' }}">
            <td>{{ $r->customer->nama }}</td>
            <td>{{ $r->product->nama }}</td>
            <td>{{ $r->interval_hari }} hari</td>
            <td>{{ optional($r->next_order_date)->format('d-m-Y') }}</td>
            <td>
                @if($r->status == 'terlambat')
                <span class="badge badge-danger">Terlambat</span>
                @elseif($r->status == 'berhenti')
                <span class="badge badge-secondary">Berhenti</span>
                @else
                <span class="badge badge-success">Aktif</span>
                @endif
            </td>
            <td>
                @php
                $lastFollowup = $r->customer->followups->first();
                @endphp
                @if($lastFollowup)
                <small>
                    {{ $lastFollowup->tgl_followup->format('d-m-Y') }}<br>
                    Hasil: <strong>{{ $lastFollowup->hasil }}</strong><br>
                    ({{ $lastFollowup->jenis }})
                </small>
                @else
                <small class="text-muted">Belum pernah</small>
                @endif
            </td>
            <td>
                <a href="{{ route('customers.show', $r->customer_id) }}" class="btn btn-sm btn-info">Lihat</a>

                @if($r->status == 'terlambat')
                <a href="{{ route('followups.create', $r->customer_id) }}" class="btn btn-sm btn-danger">Follow-up
                    Lagi</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $repeats->links() }}
@endsection