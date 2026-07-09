@extends('layouts.app')

@section('content')
    <div class="container pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-light d-print-header">
            <div>
                <h2 class="h1 mb-0 text-dark fw-bold"><i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Relatório
                    Estatístico e de Performance</h2>
                <small class="text-muted d-none d-print-block">Gerado em: {{ now()->format('d/m/Y \à\s H:i') }} | Stand
                    Gestão Pro</small>
            </div>
            <button onclick="window.print()" class="btn btn-primary d-print-none shadow-sm">
                <i class="bi bi-printer me-1"></i> Gerar resumo PDF
            </button>
        </div>

        <div class="seccao-resumos-global">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border border-light-subtle shadow-premium-sm bg-white h-100 overflow-hidden">
                        <div class="card-header bg-primary text-white px-4 py-3 border-0">
                            <h6 class="text-uppercase tracking-wider small fw-bold mb-0" style="opacity: 0.9;">Faturação
                                Total</h6>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div>
                                <h2 class="mb-0 fw-bold display-6 text-dark">
                                    {{ number_format($totalSalesValue, 2, ',', '.') }} €</h2>
                            </div>
                            <div class="mt-3 small text-muted border-top border-light-subtle pt-2">
                                Proveniente de <span class="fw-bold text-primary">{{ $soldVehicles }}</span> viaturas
                                vendidas
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border border-light-subtle shadow-premium-sm bg-white h-100 overflow-hidden">
                        <div class="card-header bg-success text-white px-4 py-3 border-0">
                            <h6 class="text-uppercase tracking-wider small fw-bold mb-0" style="opacity: 0.9;">Valor de
                                Stock Ativo</h6>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div>
                                <h2 class="mb-0 fw-bold display-6 text-dark">{{ number_format($stockValue, 2, ',', '.') }} €
                                </h2>
                            </div>
                            <div class="mt-3 small text-muted border-top border-light-subtle pt-2">
                                <span class="fw-bold text-success">{{ $availableVehicles }}</span> carros prontos a
                                comercializar
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border border-light-subtle shadow-premium-sm bg-white h-100 overflow-hidden">
                        <div class="card-header bg-dark text-white px-4 py-3 border-0">
                            <h6 class="text-uppercase tracking-wider small fw-bold mb-0" style="opacity: 0.9;">Idade Média
                                do Stock</h6>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div>
                                <h2 class="mb-0 fw-bold display-6 text-dark">{{ round($averageAge, 1) }} Anos</h2>
                            </div>
                            <div class="mt-3 small text-muted border-top border-light-subtle pt-2">
                                Média geral das viaturas disponíveis
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-5 page-break-block seccao-resumos-global">
                <div class="card shadow-premium border border-light-subtle rounded-3 h-100 overflow-hidden">
                    <div class="card-header bg-light px-4 py-3 border-bottom border-light-subtle">
                        <h5 class="mb-0 fw-bold h6 text-dark text-uppercase tracking-wide">Resumo de Inventário</h5>
                    </div>
                    <div class="card-body p-0 bg-white">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                <tr class="border-bottom border-light">
                                    <td class="p-3 text-secondary">Total de Viaturas Registadas (Histórico)</td>
                                    <td class="p-3 text-end fw-bold text-dark"># {{ $totalVehicles }}</td>
                                </tr>
                                <tr class="border-bottom border-light">
                                    <td class="p-3 text-secondary">Viaturas Disponíveis em Parque</td>
                                    <td class="p-3 text-end text-success fw-bold">{{ $availableVehicles }}</td>
                                </tr>
                                <tr class="border-bottom border-light">
                                    <td class="p-3 text-secondary">Viaturas Vendidas</td>
                                    <td class="p-3 text-end text-primary fw-bold">{{ $soldVehicles }}</td>
                                </tr>
                                <tr class="bg-dark text-white table-print-dark">
                                    <td class="p-3 fw-bold">Potencial de Negócio (Stock + Vendas)</td>
                                    <td class="p-3 text-end fw-bold">
                                        {{ number_format($stockValue + $totalSalesValue, 2, ',', '.') }} €</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 page-break-block card-vendas-container">
                <div class="card shadow-premium border border-light-subtle rounded-3 overflow-hidden h-100 d-flex flex-column"
                    id="seccao-listagem-vendas">
                    <div
                        class="card-header bg-light px-4 py-3 border-bottom border-light-subtle d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 fw-bold h6 text-dark text-uppercase tracking-wide">Vendas</h5>
                        </div>

                        <form action="{{ route('admin.reports') }}" method="GET"
                            class="d-print-none d-flex flex-wrap align-items-center gap-2 m-0">
                            <div class="d-flex align-items-center gap-2">
                                <label class="small text-muted mb-0 text-nowrap" style="font-size: 0.75rem;">De:</label>
                                <input type="date" name="start_date"
                                    class="form-control form-control-sm border-light-subtle"
                                    value="{{ request('start_date', now()->startOfYear()->format('Y-m-d')) }}"
                                    style="font-size: 0.8rem; border-radius: 4px; width: 130px;">
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <label class="small text-muted mb-0 text-nowrap" style="font-size: 0.75rem;">Até:</label>
                                <input type="date" name="end_date"
                                    class="form-control form-control-sm border-light-subtle"
                                    value="{{ request('end_date', now()->format('Y-m-d')) }}"
                                    style="font-size: 0.8rem; border-radius: 4px; width: 130px;">
                            </div>

                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-sm btn-primary px-3 fw-bold"
                                    style="font-size: 0.75rem; border-radius: 4px;">OK</button>
                                <button type="button" onclick="imprimirApenasVendas()"
                                    class="btn btn-sm btn-outline-primary d-print-none shadow-sm py-1 px-2"
                                    style="font-size: 0.75rem; border-radius: 4px;">
                                    <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="card-body p-0 bg-white">
                        <div class="table-responsive-scroll">
                            <table class="table table-hover align-middle mb-0 text-center">
                                <thead class="table-light sticky-top" style="z-index: 1;">
                                    <tr>
                                        <th class="text-start ps-4">Data</th>
                                        <th class="text-start">Viatura</th>
                                        <th>Cliente</th>
                                        <th class="text-end pe-4">Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salesPerPeriod ?? [] as $sale)
                                        <tr class="border-bottom border-light" style="height: 48px;">
                                            <td class="text-start ps-4 text-secondary small">
                                                {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="text-start fw-medium text-dark">
                                                {{ $sale->vehicle->make ?? 'N/D' }} {{ $sale->vehicle->model ?? '' }}
                                            </td>
                                            <td class="text-secondary small">
                                                {{ $sale->client->name ?? 'N/D' }}
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-dark">
                                                {{ number_format($sale->sale_amount, 2, ',', '.') }} €
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (empty($salesPerPeriod) || count($salesPerPeriod) === 0)
                                        <tr>
                                            <td colspan="4" class="text-muted p-4 small">Nenhum registo de venda
                                                encontrado para o intervalo selecionado.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="seccao-resumos-global">
            <div class="row g-4 mb-4 page-break-block">
                <div class="col-md-6">
                    <div class="card shadow-premium border border-light-subtle rounded-3 h-100 overflow-hidden">
                        <div class="card-header bg-light px-4 py-3 border-bottom border-light-subtle">
                            <h5 class="mb-0 fw-bold h6 text-dark text-uppercase tracking-wide">Modelos Mais Rentáveis /
                                Vendidos</h5>
                        </div>
                        <div class="card-body p-0 bg-white">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Viatura</th>
                                        <th class="text-center">Qtd</th>
                                        <th class="text-end pe-4">Volume Comercial</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topVehicles ?? [] as $item)
                                        <tr class="border-bottom border-light">
                                            <td class="ps-4 fw-medium text-dark">{{ $item->make }} {{ $item->model }}
                                            </td>
                                            <td class="text-center text-secondary fw-semibold">{{ $item->qty }}</td>
                                            <td class="text-end pe-4 fw-bold text-dark">
                                                {{ number_format($item->total_value, 2, ',', '.') }} €</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-premium border border-light-subtle rounded-3 h-100 overflow-hidden">
                        <div class="card-header bg-light px-4 py-3 border-bottom border-light-subtle">
                            <h5 class="mb-0 fw-bold h6 text-dark text-uppercase tracking-wide">Eficácia do Funil CRM</h5>
                        </div>
                        <div class="card-body p-4 bg-white">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small fw-bold text-secondary mb-1">
                                    <span>Taxa de Conversão Global</span>
                                    <span class="text-primary">{{ $crmConversionRate ?? '0' }}%</span>
                                </div>
                                <div class="progress rounded-pill bg-light" style="height: 12px;">
                                    <div class="progress-bar bg-primary rounded-pill" role="progressbar"
                                        style="width: {{ $crmConversionRate ?? 0 }}%"></div>
                                </div>
                            </div>
                            <div class="row g-3 text-center mt-2">
                                <div class="col-6 border-end border-light">
                                    <span class="small text-muted text-uppercase fw-bold d-block"
                                        style="font-size: 0.68rem;">Total Ações CRM</span>
                                    <strong class="fs-4 text-dark">{{ $totalCrmActions ?? 0 }}</strong>
                                </div>
                                <div class="col-6">
                                    <span class="small text-muted text-uppercase fw-bold d-block"
                                        style="font-size: 0.68rem;">Vendas Concluídas</span>
                                    <strong class="fs-4 text-dark">{{ $soldVehicles }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4 page-break-block">
                <div class="col-md-6">
                    <div class="card shadow-premium border border-light-subtle rounded-3 h-100 overflow-hidden">
                        <div class="card-header bg-light px-4 py-3 border-bottom border-light-subtle">
                            <h5 class="mb-0 fw-bold h6 text-dark text-uppercase tracking-wide">Tempo de Rotatividade de
                                Stock</h5>
                        </div>
                        <div class="card-body p-4 bg-white d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-bold text-uppercase d-block mb-1"
                                    style="font-size: 0.72rem;">Permanência Média em Parque</span>
                                <h3 class="mb-0 fw-bold text-dark">{{ $avgStockDays ?? '0' }} Dias</h3>
                            </div>
                            <div class="bg-light p-3 rounded-circle border border-light-subtle">
                                <i class="bi bi-hourglass-split text-secondary fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-premium border border-light-subtle rounded-3 h-100 overflow-hidden">
                        <div class="card-header bg-light px-4 py-3 border-bottom border-light-subtle">
                            <h5 class="mb-0 fw-bold h6 text-dark text-uppercase tracking-wide">Origem de Clientes</h5>
                        </div>
                        <div class="card-body p-0 bg-white">
                            <table class="table table-hover align-middle mb-0 text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-start ps-4">Canal</th>
                                        <th>Interações</th>
                                        <th class="text-end pe-4">Percentagem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leadsChannels ?? [] as $channel)
                                        <tr class="border-bottom border-light">
                                            <td class="text-start ps-4 fw-medium text-dark">{{ $channel->name }}</td>
                                            <td class="text-secondary fw-semibold">{{ $channel->count }}</td>
                                            <td class="text-end pe-4 fw-bold text-primary">{{ $channel->percentage }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        html {
            overflow-y: scroll;
        }

        .shadow-premium {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.330) !important;
        }

        .shadow-premium-sm {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.330) !important;
        }

        .table-responsive-scroll {
            max-height: 210px;
            overflow-y: auto;
        }

        @media print {

            nav,
            .navbar,
            #app-navbar,
            .nav-sidebar,
            .d-print-none,
            .alert {
                display: none !important;
            }

            body {
                background-color: #ffffff !important;
                color: #000000 !important;
                font-size: 12px;
            }

            .card {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                box-shadow: none !important;
            }

            .table-responsive-scroll {
                max-height: none !important;
                overflow-y: visible !important;
            }

            body.print-sales-only .seccao-resumos-global {
                display: none !important;
            }

            body.print-sales-only .card-vendas-container {
                width: 100% !important;
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
        }

        .card-vendas-container .card-body {
            flex: 1 1 auto;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .table-responsive-scroll {
            flex: 1 1 auto;
            overflow-y: auto;
            max-height: 280px;
    </style>

    <script>
        function imprimirApenasVendas() {
            document.body.classList.add('print-sales-only');
            window.print();
            document.body.classList.remove('print-sales-only');
        }
    </script>
@endsection
