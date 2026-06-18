@extends('layouts.app')

@section('content')
<div class="container-fluid bg-dark text-white py-5 my-5 rounded shadow">
    <div class="row justify-content-center align-items-center py-5">
        <div class="col-md-8 text-center">
            <div class="mb-4">
                <i class="bi bi-speedometer2 text-primary" style="font-size: 4rem;"></i>
            </div>

            <h1 class="display-4 fw-bold mb-3">Stand Eduardo Pereira</h1>
            <p class="lead text-muted mb-4 fs-4">
                Plataforma de Gestão Integrada de Viaturas, Clientes e Vendas.
            </p>

            <hr class="my-4 border-secondary style-2">

            <p class="mb-5 text-secondary">
                Aplicação Web desenvolvida em Laravel para a Avaliação Prática de 2026.
                Permite o controlo total de stock, rastreio de interações CRM e relatórios de faturação.
            </p>

            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                @auth
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4 gap-3 shadow">
                        <i class="bi bi-speedometer text-white me-2"></i>Ir para o Dashboard
                    </a>
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-light btn-lg px-4">
                        Ver Vendas
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 gap-3 shadow">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sessão
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection
