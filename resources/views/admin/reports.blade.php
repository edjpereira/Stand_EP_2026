@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-bar-chart-line-fill"></i> Relatório Estatístico e de Performance</h2>
        <button onclick="window.print()" class="btn btn-outline-primary d-print-none">
            <i class="bi bi-printer"></i> Imprimir / Guardar PDF
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="text-white-50 text-uppercase small font-weight-bold">Faturação Total</h6>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($totalSalesValue, 2, ',', '.') }} €</h2>
                    </div>
                    <div class="mt-3 small text-white-50">Proveniente de {{ $soldVehicles }} viaturas vendidas</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="text-white-50 text-uppercase small font-weight-bold">Valor de Stock Ativo</h6>
                        <h2 class="mb-0 font-weight-bold">{{ number_format($stockValue, 2, ',', '.') }} €</h2>
                    </div>
                    <div class="mt-3 small text-white-50">{{ $availableVehicles }} carros prontos a comercializar</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-dark text-white h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="text-white-50 text-uppercase small font-weight-bold">Idade Média do Stock</h6>
                        <h2 class="mb-0 font-weight-bold">{{ round($averageAge, 1) }} Anos</h2>
                    </div>
                    <div class="mt-3 small text-white-50">Média geral das viaturas disponíveis</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 font-weight-bold text-secondary">Resumo de Inventário</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Métrica do Sistema</th>
                        <th class="text-end">Quantidade / Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total de Viaturas Registadas (Histórico)</td>
                        <td class="text-end font-weight-bold"># {{ $totalVehicles }}</td>
                    </tr>
                    <tr>
                        <td>Viaturas Disponíveis em Parque</td>
                        <td class="text-end text-success font-weight-bold">{{ $availableVehicles }}</td>
                    </tr>
                    <tr>
                        <td>Viaturas Vendidas</td>
                        <td class="text-end text-primary font-weight-bold">{{ $soldVehicles }}</td>
                    </tr>
                    <tr class="table-dark text-white">
                        <td><strong>Potencial de Negócio (Stock + Vendas)</strong></td>
                        <td class="text-end"><strong>{{ number_format($stockValue + $totalSalesValue, 2, ',', '.') }} €</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="alert alert-info shadow-sm border-0 d-print-none" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Dica para o Relatório:</strong> Podes utilizar esta página para demonstrar a capacidade do sistema em cruzar estados de stock e valores financeiros em tempo real.
    </div>
</div>

<style>
    html { overflow-y: scroll; }

    /* Regras exclusivas para quando a página é impressa ou guardada em PDF */
    @media print {
        /* Esconde a barra de navegação do topo */
        nav, .navbar, #app-navbar, .nav-sidebar {
            display: none !important;
        }

        /* Remove margens desnecessárias do topo criadas pela navbar fixa */
        body {
            padding-top: 0 !important;
            margin-top: 0 !important;
            background-color: #fff !important;
        }

        /* Garante que os cartões coloridos mantêm as cores de fundo no PDF */
        .card {
            background-color: unset !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .bg-primary { background-color: #0d6efd !important; }
        .bg-success { background-color: #198754 !important; }
        .bg-dark { background-color: #212529 !important; }
        .text-white { color: #fff !important; }
        .table-dark { background-color: #212529 !important; color: #fff !important; }
    }
</style>
@endsection
