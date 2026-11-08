@extends('layouts.app')
@section('content')
<h3>Edit Produk</h3>
<form action="{{ route('products.update', $product) }}" method="POST">
    @csrf @method('PUT')
    @include('products._form', ['product' => $product])
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection