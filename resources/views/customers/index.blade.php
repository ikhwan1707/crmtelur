@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Daftar Pelanggan</h3>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Tambah</a>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Kategori</th>
            <th>No WA</th>
            <th>Status</th>
            <th>Tgl Daftar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $c)
        <tr>
            <td>{{ $c->nama }}</td>
            <td>{{ $c->kategori }}</td>
            <td>{{ $c->no_wa }}</td>
            <td>{{ $c->status }}</td>
            <td>{{ $c->tgl_daftar->format('d-m-Y') }}</td>
            <td>
                <a href="{{ route('customers.show', $c) }}" class="btn btn-sm btn-info">Lihat</a>
                <a href="{{ route('customers.edit', $c) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('customers.destroy', $c) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $customers->links() }}
@endsection