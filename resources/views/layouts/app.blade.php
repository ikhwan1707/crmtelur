<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRM Telur</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- TAMBAHKAN BARIS INI: Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">CRM Telur</a>

            @auth
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('customers.index') }}">Pelanggan</a>
                <a class="nav-link" href="{{ route('products.index') }}">Produk</a>
                <a class="nav-link" href="{{ route('orders.index') }}">Pesanan</a>
                <a class="nav-link" href="{{ route('repeat.index') }}">Pesanan Berulang</a>
                <a class="nav-link" href="{{ route('followups.index') }}">Follow-up</a>
                <a class="nav-link" href="{{ route('reports.index') }}">Laporan</a>
            </div>

            <div class="navbar-nav ml-auto">
                <span class="navbar-text mr-3">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-light">Logout</button>
                </form>
            </div>
            @endauth

            @guest
            <div class="navbar-nav ml-auto">
                <a class="nav-link" href="{{ route('login') }}">Login</a>
                @if (Route::has('register'))
                <a class="nav-link" href="{{ route('register') }}">Register</a>
                @endif
            </div>
            @endguest
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif
        @yield('content')
    </div>
</body>

</html>