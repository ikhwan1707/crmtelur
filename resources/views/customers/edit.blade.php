@extends('layouts.app')
@section('content')
<h3>Edit Pelanggan</h3>
<form action="{{ route('customers.update', $customer) }}" method="POST">
    @csrf @method('PUT')
    @include('customers._form', ['customer' => $customer])
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection