@extends('layouts.app')

@section('content')
    <div class="container pt-4">
        {{-- Cabeçalho Superior da Página --}}
        <div class="d-flex justify-content-end align-items-center mb-4">
            <a href="{{ route('sales.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left me-1"></i> Voltar à Listagem
            </a>
        </div>

        {{-- Bloco Principal: Detalhes da Venda --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card shadow-premium border border-light-subtle rounded-3 overflow-hidden">
                    <div
                        class="card-header bg-light-subtle d-flex justify-content-between align-items-center px-4 py-3 border-bottom border-light-subtle">
                        <div class="d-flex align-items-center gap-2 text-truncate">
                            <i class="bi bi-search text-secondary fs-5"></i>
                            <h3 class="h1 mb-0 text-dark fw-bold">Venda nº {{ $sale->id }}</h3>
                        </div>

                        {{-- Conjunto de Botões Uniformizado (Estilo Baguette do Index/Client Show) --}}
                        @if (auth()->user()->role === 'admin')
                            <div class="action-buttons-wrapper">
                                <div class="btn-group" role="group" aria-label="Ações da Venda">
                                    {{-- 1. Botão Editar --}}
                                    <a href="{{ route('sales.edit', $sale->id) }}"
                                        class="btn btn-action-group btn-group-default" data-bs-toggle="tooltip"
                                        title="Editar Venda">
                                        <i class="bi bi-pencil text-dark"></i>
                                    </a>

                                    {{-- 2. Botão Eliminar --}}
                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                        class="d-inline m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-action-group btn-group-danger btn-group-default"
                                            onclick="return confirm('Tem a certeza que deseja eliminar permanentemente esta venda?')"
                                            data-bs-toggle="tooltip" title="Eliminar Venda">
                                            <i class="bi bi-trash3 text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="card-body bg-white p-4">
                        <div class="row g-4">
                            {{-- Coluna Esquerda: Dados do Cliente --}}
                            <div class="col-md-6 border-end border-light">
                                <h2 class="fw-bold text-black h5 text-uppercase tracking-wider text-muted mb-3"">
                                    <i class="bi bi-person me-1"></i> Dados do Cliente
                                </h2>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                            style="font-size: 0.72rem;">Nome Completo</small>
                                        <span class="text-dark fw-medium fs-5">{{ $sale->client->name }}</span>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                            style="font-size: 0.72rem;">NIF / Identificação Fiscal</small>
                                        <span class="text-dark fw-medium">{{ $sale->client->taxId ?? '---' }}</span>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                            style="font-size: 0.72rem;">Telemóvel / Telefone</small>
                                        <span class="text-dark fw-medium">{{ $sale->client->phone ?? '---' }}</span>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                            style="font-size: 0.72rem;">Endereço Eletrónico</small>
                                        <a href="mailto:{{ $sale->client->email }}"
                                            class="text-primary text-decoration-none fw-medium">{{ $sale->client->email }}</a>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="action-buttons-wrapper">
                                            <a href="{{ route('clients.show', $sale->client->id) }}"
                                                class="btn btn-action-group btn-group-default px-3 d-inline-flex align-items-center gap-2"
                                                style="width: auto !important; height: 36px;" data-bs-toggle="tooltip"
                                                title="Ver detalhes do cliente">
                                                <i class="bi bi-eye text-dark"></i>
                                                <span class="text-dark fw-medium" style="font-size: 0.85rem;"> Ver
                                                    cliente</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Coluna Direita: Dados da Viatura --}}
                            <div class="col-md-6 ps-md-4">
                                <h2 class="fw-bold text-black h5 text-uppercase tracking-wider text-muted mb-3">
                                    <i class="bi bi-car-front me-1"></i> Dados da Viatura
                                </h2>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                            style="font-size: 0.72rem;">Fabricante</small>
                                        <span class="text-dark fw-medium fs-5">{{ $sale->vehicle->make }}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                            style="font-size: 0.72rem;">Modelo</small>
                                        <span class="text-dark fw-medium fs-5">{{ $sale->vehicle->model }}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                            style="font-size: 0.72rem;">Matrícula</small>
                                        <div class="d-inline-flex align-items-center border border-dark rounded bg-white text-dark fw-bold px-2 py-0 mt-1"
                                            style="font-size: 0.72rem; height: 18px; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
                                            <span
                                                class="bg-primary text-white d-flex flex-column align-items-center justify-content-center rounded-start px-1"
                                                style="font-size: 5px; margin-left: -8px; margin-right: 4px; height: 100%; min-width: 9px;">
                                                <span>P</span>
                                            </span>
                                            <span style="letter-spacing: 0.5px;">{{ $sale->vehicle->plate }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                            style="font-size: 0.72rem;">Ano de Fabrico</small>
                                        <span class="text-dark fw-medium">{{ $sale->vehicle->year }}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                            style="font-size: 0.72rem;">Quilometragem</small>
                                        <span
                                            class="text-dark fw-medium">{{ number_format($sale->vehicle->mileage, 0, ',', '.') }}
                                            km</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-uppercase text-muted fw-bold d-block mb-1"
                                            style="font-size: 0.72rem;">Preço de Tabela</small>
                                        <span
                                            class="text-secondary fw-medium">{{ number_format($sale->vehicle->price, 2, ',', '.') }}
                                            €</span>
                                    </div>
                                    <div class="col-12 mt-2">
                                    <div class="action-buttons-wrapper">
                                        <a href="{{ route('vehicles.show', $sale->vehicle->id) }}"
                                            class="btn btn-action-group btn-group-default px-3 d-inline-flex align-items-center gap-2"
                                            style="width: auto !important; height: 36px;" 
                                            data-bs-toggle="tooltip" title="Ver detalhes da viatura">
                                            <i class="bi bi-eye text-dark"></i>
                                            <span class="text-dark fw-medium" style="font-size: 0.85rem;"> Ver
                                                detalhes</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row bg-light-subtle p-4 rounded-3 border border-light-subtle mt-4 align-items-center g-3">
                        <div class="col-md-8">
                            <small class="text-uppercase tracking-wider text-muted fw-bold d-block mb-1"
                                style="font-size: 0.72rem;">Observações da Negociação</small>
                            <div class="text-secondary bg-white p-3 rounded-2 border border-light-subtle"
                                style="white-space: pre-wrap; line-height: 1.5; font-size: 0.9rem;">
                                {{ $sale->notes ?? 'Nenhuma observação ou requisito especial registado para esta transação.' }}
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end d-flex flex-column justify-content-center align-items-md-end">
                            <small class="text-uppercase tracking-wider text-muted fw-bold mb-1"
                                style="font-size: 0.72rem;">Valor de Fecho Comercial</small>
                            @if ($sale->sale_amount >= $sale->vehicle->price)
                                <h2 class="text-success fw-bold mb-2" style="letter-spacing: -0.5px;">
                                    {{ number_format($sale->sale_amount, 2, ',', '.') }} €</h2>
                            @else
                                <h2 class="text-danger fw-bold mb-2" style="letter-spacing: -0.5px;">
                                    {{ number_format($sale->sale_amount, 2, ',', '.') }} €</h2>
                            @endif

                            <div class="action-buttons-wrapper mt-1">
                                <a href="{{ route('sales.invoice', $sale->id) }}"
                                   target="_blank"
                                   class="btn btn-action-group btn-group-default px-3 d-inline-flex align-items-center gap-2"
                                   style="width: auto !important; height: 36px;"
                                   data-bs-toggle="tooltip" title="Gerar Fatura em PDF">
                                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                                    <span class="text-dark fw-medium" style="font-size: 0.85rem;">Gerar Fatura</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light-subtle border-top border-light-subtle px-4 py-2 text-end">
                    <small class="text-muted" style="font-size: 0.78rem;">Venda faturada a:
                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</small>
                </div>
            </div>
        </div>
    </div>
    </div>

    <style>
        .shadow-premium {
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03) !important;
        }

        .action-buttons-wrapper {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.09), 0 1px 4px rgba(0, 0, 0, 0.04) !important;
            border-radius: 20px;
            overflow: hidden;
            display: inline-flex;
            border: 1px solid #e2e8f0;
        }

        .btn-action-group {
            width: 47px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none !important;
            transition: all 0.15s ease-in-out;
            margin: 0;
            border-radius: 0 !important;
        }

        .btn-action-group i {
            font-size: 1.05rem;
            line-height: 1;
        }

        .btn-action-group:not(:first-child),
        .btn-group>form:not(:first-child) .btn-action-group {
            border-left: 1px solid #e2e8f0 !important;
        }

        .btn-group-default {
            background-color: #ffffff !important;
        }

        .btn-group-default:hover {
            background-color: #f8f9fa !important;
        }

        .btn-group-danger:hover {
            background-color: rgba(220, 53, 69, 0.08) !important;
        }
    </style>
@endsection
