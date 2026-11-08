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
        <select name="metode_bayar" class="form-control">
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
    <button class="btn btn-primary">Update</button>
</form>
@endsection