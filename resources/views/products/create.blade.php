@extends('layouts.app')
@section('content')
<h3>Tambah Produk</h3>
<form action="{{ route('products.store') }}" method="POST">
    @csrf
    @include('products._form', ['product' => null])
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection