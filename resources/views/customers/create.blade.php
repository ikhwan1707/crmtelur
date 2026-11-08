@extends('layouts.app')
@section('content')
<h3>Tambah Pelanggan</h3>
<form action="{{ route('customers.store') }}" method="POST">
    @csrf
    @include('customers._form', ['customer' => null])
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection