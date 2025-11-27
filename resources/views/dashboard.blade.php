@extends('layouts.app')

@section('title', 'Dashboard - SiBBesar')

@section('content')
<div class="dashboard-header">
    <h1 class="dashboard-title">Dashboar</h1>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <!-- Jurnal Umum -->
    <a href="{{ route('jurnal.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Jurnal Umum</div>
            <div class="stat-value">{{ $journalCount }}</div>
        </div>
        <div class="stat-icon purple">
            <i class="fas fa-book"></i>
        </div>
    </a>

    <!-- Data Akun -->
    <a href="{{ route('akun.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Data Akun</div>
            <div class="stat-value">{{ $accountCount }}</div>
        </div>
        <div class="stat-icon orange">
            <i class="fas fa-list-alt"></i>
        </div>
    </a>
    
    <!-- Hutang -->
    <a href="{{ route('hutang.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Hutang</div>
            <div class="stat-label" style="font-size: 14px; color: #dc3545; font-weight: 600;">Rp {{ number_format($totalHutang, 0, ',', '.') }}</div>
            <div class="stat-value" style="font-size: 18px;">{{ $countHutang }} transaksi</div>
        </div>
        <div class="stat-icon" style="background-color: #fee2e2;">
            <i class="fas fa-money-bill-wave" style="color: #dc3545;"></i>
        </div>
    </a>
    
    <!-- Piutang -->
    <a href="{{ route('piutang.index') }}" class="stat-card" style="text-decoration: none; color: inherit;">
        <div class="stat-content">
            <div class="stat-label">Piutang</div>
            <div class="stat-label" style="font-size: 14px; color: #198754; font-weight: 600;">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</div>
            <div class="stat-value" style="font-size: 18px;">{{ $countPiutang }} transaksi</div>
        </div>
        <div class="stat-icon" style="background-color: #d1fae5;">
            <i class="fas fa-hand-holding-usd" style="color: #198754;"></i>
        </div>
    </a>
</div>

<!-- Chart Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">
                    <i class="fas fa-chart-area text-primary me-2"></i>
                    Grafik Pemasukan & Pengeluaran
                </h5>
                <div class="d-flex align-items-center">
                    <label for="yearSelect" class="me-2 mb-0 text-muted">Tahun:</label>
                    <select id="yearSelect" class="form-select form-select-sm" style="width: 120px;">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="journalChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>


@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
    }
    
    .card-header {
        border-bottom: 1px solid #e5e7eb;
        border-radius: 10px 10px 0 0 !important;
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    let journalChart = null;
    
    // Function to load chart data
    function loadChartData(year) {
        fetch(`{{ route('dashboard.chart-data') }}?year=${year}`)
            .then(response => response.json())
            .then(data => {
                updateChart(data);
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
            });
    }
    
    // Function to create/update chart
    function updateChart(data) {
        const ctx = document.getElementById('journalChart').getContext('2d');
        
        // Destroy existing chart if it exists
        if (journalChart) {
            journalChart.destroy();
        }
        
        // Create gradient for IN (Pemasukan)
        const gradientIn = ctx.createLinearGradient(0, 0, 0, 400);
        gradientIn.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
        gradientIn.addColorStop(1, 'rgba(16, 185, 129, 0.1)');
        
        // Create gradient for OUT (Pengeluaran)
        const gradientOut = ctx.createLinearGradient(0, 0, 0, 400);
        gradientOut.addColorStop(0, 'rgba(239, 68, 68, 0.8)');
        gradientOut.addColorStop(1, 'rgba(239, 68, 68, 0.1)');
        
        journalChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Pemasukan (IN)',
                        data: data.dataIn,
                        backgroundColor: gradientIn,
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: 'rgb(16, 185, 129)',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3
                    },
                    {
                        label: 'Pengeluaran (OUT)',
                        data: data.dataOut,
                        backgroundColor: gradientOut,
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: 'rgb(239, 68, 68)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: 'rgb(239, 68, 68)',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 13,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                    notation: 'compact',
                                    compactDisplay: 'short'
                                }).format(value);
                            },
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        }
                    }
                }
            }
        });
    }
    
    // Load initial data
    document.addEventListener('DOMContentLoaded', function() {
        const yearSelect = document.getElementById('yearSelect');
        loadChartData(yearSelect.value);
        
        // Reload chart when year changes
        yearSelect.addEventListener('change', function() {
            loadChartData(this.value);
        });
    });
</script>
@endpush

@endsection