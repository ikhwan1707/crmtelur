@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Daftar Produk</h3>
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Tambah</a>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $p)
        <tr>
            <td>{{ $p->nama }}</td>
            <td>{{ $p->satuan }}</td>
            <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
            <td>
                <a href="{{ route('products.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('products.destroy', $p) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $products->links() }}
@endsection