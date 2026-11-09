@extends('layouts.app')
@section('content')
<h3>Edit Pesanan #{{ $order->id }}</h3>
<form action="{{ route('orders.update', $order) }}" method="POST">
    @csrf @method('PUT')

    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control">
            @foreach(['diproses','dikirim','selesai','batal'] as $s)
            <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Metode Bayar</label>
        {{-- Tambahkan id dan onchange untuk memicu JS --}}
        <select name="metode_bayar" id="metode_bayar" class="form-control" onchange="toggleJatuhTempo()">
            @foreach(['tunai','tempo'] as $m)
            <option value="{{ $m }}" {{ $order->metode_bayar == $m ? 'selected' : '' }}>{{ $m }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Tgl Kirim</label>
        <input type="date" name="tgl_kirim" class="form-control"
            value="{{ optional($order->tgl_kirim)->format('Y-m-d') }}">
    </div>

    {{-- TAMBAHAN INPUT JATUH TEMPO --}}
    <div class="form-group" id="form_jatuh_tempo" style="display: none;">
        <label>Tgl Jatuh Tempo</label>
        <input type="date" name="tgl_jatuh_tempo" class="form-control"
            value="{{ optional($order->tgl_jatuh_tempo)->format('Y-m-d') }}">
        <small class="text-muted">Biarkan kosong jika tidak ada batas waktu.</small>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">Batal</a>
</form>

{{-- TAMBAHKAN SCRIPT INI --}}
<script>
    function toggleJatuhTempo() {
        const metode = document.getElementById('metode_bayar').value;
        const formTempo = document.getElementById('form_jatuh_tempo');
        if (metode === 'tempo') {
            formTempo.style.display = 'block';
        } else {
            formTempo.style.display = 'none';
        }
    }
    // Jalankan saat halaman pertama kali dimuat
    toggleJatuhTempo();
</script>
@endsection