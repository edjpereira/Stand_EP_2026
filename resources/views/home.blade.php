@extends('layouts.app')

@section('content')
<div class="container">
    {{-- NOTIFICAÇÃO A: PEDIDO ACEITE --}}
    @if(Auth::user()->admin_request_status === 'approved')
        <div class="alert alert-success border-0 shadow-sm rounded-4 p-3 my-3 d-flex align-items-center justify-content-between flex-wrap gap-2 animate__animated animate__fadeIn">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 p-2 rounded-circle text-success me-3">
                    <i class="bi bi-shield-check fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Parabéns! O teu pedido foi aceite.</h6>
                    <span class="small text-muted">A tua conta foi promovida com sucesso para o nível de <strong>Administrador</strong>.</span>
                </div>
            </div>
            <a href="#" onclick="event.preventDefault(); document.getElementById('form-dismiss-notification').submit();" class="btn btn-sm btn-success px-4 fw-bold rounded-3 text-decoration-none">
                Aceito
            </a>
        </div>
    @endif

    {{-- NOTIFICAÇÃO B: PEDIDO REJEITADO (Movido para aqui) --}}
    @if(Auth::user()->admin_request_status === 'rejected')
        <div class="alert alert-danger border-0 shadow-sm rounded-4 p-3 my-3 d-flex align-items-center justify-content-between flex-wrap gap-2 animate__animated animate__fadeIn">
            <div class="d-flex align-items-center">
                <div class="bg-danger bg-opacity-10 p-2 rounded-circle text-danger me-3">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Pedido de Acesso Recusado</h6>
                    <span class="small text-muted">Lamentamos, mas o teu pedido para o nível Admin não foi aprovado pelos administradores.</span>
                </div>
            </div>
            <a href="#" onclick="event.preventDefault(); document.getElementById('form-dismiss-notification').submit();" class="btn btn-sm btn-danger px-4 fw-bold rounded-3 text-decoration-none">
                Aceito
            </a>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success mt-3 shadow-sm rounded-3">{{ session('success') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success mt-3 shadow-sm rounded-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-3 shadow-sm rounded-3">{{ session('error') }}</div>
    @endif

    {{-- Cabeçalho Principal --}}
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

            {{-- Zona de Ações à Direita --}}
            <div class="d-flex align-items-center gap-2">
                {{-- Apenas Employees e Admins veem o botão de venda --}}
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'employee')
                    <button type="button" class="btn btn-sm btn-success rounded-3 shadow-sm text-white px-3" data-bs-toggle="modal" data-bs-target="#startSaleModal">
                        <i class="bi bi-cart-plus me-1"></i>Iniciar Venda
                    </button>
                @endif

                <a href="{{ route('users.edit', auth()->id()) }}" class="btn btn-sm btn-outline-primary rounded-3 text-white border-secondary">
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

    {{-- Resto do Dashboard: Gráficos e Cards --}}
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

@if(Auth::user()->role === 'admin' || Auth::user()->role === 'employee')
    <div class="modal fade" id="startSaleModal" tabindex="-1" aria-labelledby="startSaleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-success text-white rounded-top-4 border-0 p-4">
                    <h5 class="modal-title fw-bold" id="startSaleModalLabel"><i class="bi bi-cash-coin me-2"></i>Registar Nova Venda</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sales.store') }}" method="POST">
                    @csrf
                    {{-- Injeta automaticamente a data de hoje requerida pelo teu validador --}}
                    <input type="hidden" name="sale_date" value="{{ now()->format('Y-m-d') }}">

                    <div class="modal-body p-4">

                        <div class="mb-3">
                            <label for="vehicle_id" class="form-label fw-bold text-dark">Selecionar Viatura (Em Stock)</label>
                            <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                                <option value="" selected disabled>Escolha a matrícula...</option>
                                @foreach($availableVehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->plate }} - {{ $vehicle->make }} {{ $vehicle->model }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="client_id" class="form-label fw-bold text-dark">Selecionar Cliente</label>
                            <select class="form-select" id="client_id" name="client_id" required>
                                <option value="" selected disabled>Escolha o cliente...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sale_amount" class="form-label fw-bold text-dark">Valor de Venda (€)</label>
                            <input type="number" step="0.01" class="form-control" id="sale_amount" name="sale_amount" placeholder="0.00" required>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label fw-bold text-dark">Notas / Observações</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Detalhes do negócio..."></textarea>
                        </div>

                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 border-0 p-3">
                        <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success px-4">Concluir Negócio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Vendas
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(range(1, 30)) !!},
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
                data: [1,4,2,5,3,6],
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
<form id="form-dismiss-notification" action="{{ route('users.dismiss_notification', Auth::user()->id) }}" method="POST" class="d-none">
    @csrf
</form>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection
