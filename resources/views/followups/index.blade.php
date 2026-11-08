@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Riwayat Follow-up</h3>
    <a href="{{ route('followups.create') }}" class="btn btn-primary">+ Follow-up</a>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Jenis</th>
            <th>Hasil</th>
            <th>Catatan</th>
            <th>Oleh</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($followups as $f)
        <tr>
            <td>{{ $f->tgl_followup->format('d-m-Y') }}</td>
            <td>{{ $f->customer->nama }}</td>
            <td>{{ $f->jenis }}</td>
            <td>{{ $f->hasil }}</td>
            <td>{{ $f->catatan }}</td>
            <td>{{ $f->user->name }}</td>
            <td>
                <form action="{{ route('followups.destroy', $f) }}" method="POST" onsubmit="return confirm('Hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $followups->links() }}
@endsection