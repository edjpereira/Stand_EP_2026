@extends('layouts.app')

@section('content')
<div class="container">
    <div class="bg-dark text-white p-4 rounded-4 shadow mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <img src="{{ Auth::user()->photo_url }}" class="rounded-circle border border-primary shadow-sm" style="width: 55px; height: 55px; object-fit: cover; display: block;" alt="Avatar">
                </div>

                <div class="ms-3" style="padding-top: 2px;">
                    <h4 class="d-flex align-items-center gap-2 flex-wrap m-0 p-0" style="line-height: 1.2;">
                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                        @if(Auth::user()->role === 'admin')
                            <span class="admin-badge-script shadow-sm">Admin</span>
                        @endif
                    </h4>
                    <p class="text-muted m-0 p-0 mt-1 small">
                        Bem-vindo ao centro de controlo do Stand Eduardo Pereira.
                    </p>
                </div>
            </div>

            <div>
                <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-primary rounded-3 text-white border-secondary">
                    <i class="bi bi-person-gear me-1"></i>Ver Perfil
                </a>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Audiowide&family=Mea+Culpa&family=Tangerine:wght@400;700&display=swap');

        .admin-badge-script {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            font-family: 'Audiowide', cursive;
            font-size: 10px;
            font-weight: lighter;
            padding: 2px 10px;
            border-radius: 50px;
            line-height: 0.8;
            margin-left: 5px;
            vertical-align: middle;
            position: relative;
            top: -2px;
        }
    </style>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">Evolução de Vendas (30 dias)</h5>
                    <div style="height: 300px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-1">Total de Clientes</h6>
                            <h2 class="fw-bold mb-0">{{ $totalClientes }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success bg-opacity-10 text-success">
                            +{{ $clientes24h }} nas últimas 24h
                        </span>
                        <div class="mt-2" style="height: 40px; width: 100%;">
                            <canvas id="clientSparkline"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-3">Volume de Vendas</h5>
                    <div class="mb-3">
                        <small class="text-muted">Este Mês</small>
                        <h4 class="fw-bold text-primary">{{ number_format($volMes, 2, ',', '.') }} €</h4>
                    </div>
                    <div>
                        <small class="text-muted">Este Ano (Acumulado)</small>
                        <h4 class="fw-bold">{{ number_format($volAno, 2, ',', '.') }} €</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Vendas
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(range(1, 30)) !!}, // Dias 1 a 30
            datasets: [{
                label: 'Vendas',
                data: {!! json_encode($vendasTrintaDias) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Mini Sparkline ilustrativo para Clientes
    const sparkCtx = document.getElementById('clientSparkline').getContext('2d');
    new Chart(sparkCtx, {
        type: 'line',
        data: {
            labels: [1,2,3,4,5,6],
            datasets: [{
                data: [1,4,2,5,3,6], // Dados apenas ilustrativos
                borderColor: '#198754',
                borderWidth: 2,
                pointRadius: 0,
                fill: false,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { display: false }, y: { display: false } }
        }
    });
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection
