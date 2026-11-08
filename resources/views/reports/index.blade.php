@extends('layouts.app')

{{-- CSS KHUSUS UNTUK CETAK --}}
@push('styles')
<style>
    @media print {

        /* Sembunyikan Navbar, Tombol, dan elemen navigasi */
        nav.navbar,
        .btn,
        .no-print {
            display: none !important;
        }

        /* Atur margin kertas */
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            background-color: #fff !important;
            font-size: 12px;
        }

        /* Hilangkan bayangan dan garis berlebih pada card */
        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
            page-break-inside: avoid;
            /* Cegah card terpotong di tengah halaman */
        }

        .card-header {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #000 !important;
            color: #000 !important;
        }

        /* Paksa teks berwarna menjadi hitam agar jelas saat dicetak */
        .text-success,
        .text-danger,
        .text-warning,
        .text-primary {
            color: #000 !important;
            font-weight: bold;
        }

        /* Ubah layout grid menjadi 1 kolom penuh saat dicetak */
        .col-md-4,
        .col-md-5,
        .col-md-7 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
            margin-bottom: 20px !important;
        }

        /* Sembunyikan background warna pada summary card */
        .bg-primary,
        .bg-danger,
        .bg-warning {
            background-color: transparent !important;
            border: 1px solid #000 !important;
            color: #000 !important;
        }

        .text-white {
            color: #000 !important;
        }

        /* Tampilkan header khusus cetak */
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
    }

    /* Sembunyikan header khusus cetak di layar monitor */
    .print-header {
        display: none;
    }
</style>
@endpush

@section('content')

{{-- HEADER KHUSUS CETAK (Hanya muncul di kertas) --}}
<div class="print-header">
    <h3 class="mb-1">LAPORAN CRM USAHA TELUR</h3>
    <p class="mb-0">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <h3 class="mb-0">Laporan Bisnis</h3>
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">Cetak Laporan</button>
</div>

{{-- RINGKASAN (SUMMARY CARDS) --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 1px;">Total Pendapatan</h6>
                <h3 class="mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 1px;">Total Piutang Berjalan
                </h6>
                <h3 class="mb-0">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-dark shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 1px;">Pelanggan Pasif (>30
                    hari)</h6>
                <h3 class="mb-0">{{ $jumlahTidakAktif }} Pelanggan</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- KOLOM KIRI --}}
    <div class="col-md-7 mb-4">
        {{-- TOP CUSTOMERS --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-white font-weight-bold">
                Top 5 Pelanggan Teratas
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Pelanggan</th>
                            <th class="text-right">Total Belanja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topCustomers as $c)
                        <tr>
                            <td>
                                {{ $c->nama }} <br>
                                <small class="text-muted">{{ ucfirst($c->kategori) }}</small>
                            </td>
                            <td class="text-right text-success font-weight-bold">
                                Rp {{ number_format($c->total_belanja ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">Belum ada data pesanan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PIUTANG --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white font-weight-bold">
                Daftar Piutang Belum Lunas
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Pesanan</th>
                            <th>Pelanggan</th>
                            <th class="text-right">Sisa Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($piutang as $o)
                        <tr>
                            <td>
                                <a href="{{ route('orders.show', $o) }}" class="text-dark text-decoration-none">#{{
                                    $o->id }}</a> <br>
                                <small class="text-muted">{{ $o->tgl_order->format('d-m-Y') }}</small>
                            </td>
                            <td>{{ $o->customer->nama }}</td>
                            <td class="text-right text-danger font-weight-bold">
                                Rp {{ number_format($o->sisaPiutang(), 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-success py-3">Hebat! Tidak ada piutang tertunggak.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-md-5 mb-4">
        {{-- PELANGGAN TIDAK AKTIF --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white font-weight-bold d-flex justify-content-between align-items-center">
                <span>Pelanggan Tidak Aktif</span>
                <span class="badge badge-warning no-print">Tanpa order > 30 hari</span>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($inactive as $c)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $c->nama }}</strong><br>
                            <small class="text-muted">{{ $c->no_wa }}</small>
                        </div>
                        <a href="{{ route('followups.create', $c->id) }}"
                            class="btn btn-sm btn-outline-primary no-print">Follow-up</a>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-4">
                        Semua pelanggan aktif. Kerja bagus!
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection