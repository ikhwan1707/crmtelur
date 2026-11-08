@extends('layouts.app')
@section('content')
<h3>Follow-up Baru</h3>
<form action="{{ route('followups.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Pelanggan</label>
        <select name="customer_id" class="form-control" required>
            @foreach($customers as $c)
            <option value="{{ $c->id }}" {{ ($selected && $selected->id == $c->id) ? 'selected' : '' }}>{{ $c->nama }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Tanggal</label>
        <input type="date" name="tgl_followup" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
    </div>
    <div class="form-group">
        <label>Jenis</label>
        <select name="jenis" class="form-control">
            @foreach(['whatsapp','telepon','kunjungan'] as $j)
            <option value="{{ $j }}">{{ $j }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Catatan</label>
        <textarea name="catatan" class="form-control"></textarea>
    </div>
    <div class="form-group">
        <label>Hasil</label>
        <select name="hasil" class="form-control">
            @foreach(['order','tunda','tidak_aktif'] as $h)
            <option value="{{ $h }}">{{ $h }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('followups.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection