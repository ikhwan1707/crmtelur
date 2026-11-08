@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Dashboard</h3>
</div>

{{-- KARTU RINGKASAN --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-left-primary shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"
                            style="font-size: 0.8rem;">Total Pelanggan</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalCustomers }}</div>
                    </div>
                    <div class="col-auto">
                        {{-- Ikon Pelanggan --}}
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-success shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1"
                            style="font-size: 0.8rem;">Total Pesanan</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                    </div>
                    <div class="col-auto">
                        {{-- Ikon Pesanan --}}
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-danger shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1"
                            style="font-size: 0.8rem;">Perlu Follow-up</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $perluFollowup }}</div>
                    </div>
                    <div class="col-auto">
                        {{-- Ikon Lonceng --}}
                        <i class="fas fa-bell fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-warning shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"
                            style="font-size: 0.8rem;">Total Piutang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($piutang, 0, ',', '.')
                            }}</div>
                    </div>
                    <div class="col-auto">
                        {{-- Ikon Uang --}}
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- AREA GRAFIK --}}
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white font-weight-bold py-3">
                {{-- Ikon Grafik di Header --}}
                <i class="fas fa-chart-line text-primary mr-2"></i> Grafik Pendapatan (7 Hari Terakhir)
            </div>
            <div class="card-body">
                <canvas id="revenueChart" style="max-height: 350px;"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- STYLE TAMBAHAN --}}
<style>
    .border-left-primary {
        border-left: 4px solid #4e73df !important;
    }

    .border-left-success {
        border-left: 4px solid #1cc88a !important;
    }

    .border-left-danger {
        border-left: 4px solid #e74a3b !important;
    }

    .border-left-warning {
        border-left: 4px solid #f6c23e !important;
    }

    .text-gray-800 {
        color: #5a5c69 !important;
    }

    .text-gray-300 {
        color: #dddfeb !important;
    }

    /* Warna abu-abu terang khusus untuk ikon */
</style>

{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        const labels = @json($chartDates);
        const dataRevenues = @json($chartRevenues);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: dataRevenues,
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'rgba(78, 115, 223, 1)',
                    pointRadius: 4,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if(value >= 1000000) return 'Rp ' + (value/1000000) + 'Jt';
                                if(value >= 1000) return 'Rp ' + (value/1000) + 'Rb';
                                return 'Rp ' + value;
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection