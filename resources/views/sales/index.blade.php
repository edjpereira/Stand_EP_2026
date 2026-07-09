@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Registo de Vendas</h2>
        <a href="{{ route('sales.create') }}" class="btn btn-primary">Nova Venda</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Venda</th>
                            <th>Cliente</th>
                            <th>Viatura</th>
                            <th>Matrícula</th>
                            <th>Preço de Venda</th>
                            <th>Data</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td>#{{ $sale->id }}</td>

                            <td>
                                <a href="{{ route('clients.show', $sale->client->id) }}" class="text-decoration-none fw-bold text-primary link-hover">
                                    {{ $sale->client->name }}
                                </a>
                            </td>

                            <td>
                                <a href="{{ route('vehicles.show', $sale->vehicle->id) }}" class="text-decoration-none text-dark link-hover">
                                    <strong>{{ $sale->vehicle->make }}</strong> {{ $sale->vehicle->model }}
                                </a>
                            </td>

                            <td>
                                <div class="d-inline-flex align-items-center gap-2">
                                    <div class="d-inline-flex align-items-center border border-dark rounded bg-white text-dark font-monospace fw-bold px-2 py-0"
                                         style="font-size: 0.85rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); height: 26px;">
                                        <span class="bg-primary text-white d-flex flex-column align-items-center justify-content-center rounded-start text-center px-1"
                                              style="font-size: 8px; margin-left: -8px; padding-top: 2px; padding-bottom: 2px; min-width: 16px; margin-right: 6px; height: 100%;">
                                            <span class="text-warning" style="font-size: 6px; line-height: 1;">★</span>
                                            <span class="fw-bold" style="line-height: 1; margin-top: 1px;">P</span>
                                        </span>
                                        <span style="letter-spacing: 0.5px;">{{ $sale->vehicle->plate }}</span>
                                    </div>

                                    <a href="{{ route('vehicles.show', $sale->vehicle->id) }}"
                                       class="btn btn-squircle-view shadow-premium-sm"
                                       data-bs-toggle="tooltip"
                                       title="Ver Ficha da Viatura">
                                        <i class="bi bi-eye text-secondary"></i>
                                    </a>
                                </div>
                            </td>

                            <td>
                                <strong>
                                    {{ number_format($sale->price > 0 ? $sale->price : $sale->vehicle->price, 2, ',', '.') }} €
                                </strong>
                            </td>
                            <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>

                            <td class="text-end text-nowrap align-middle" style="width: 160px;">
                                <div class="btn-group shadow-premium" role="group" aria-label="Ações de Venda">

                                    <a href="{{ route('sales.show', $sale->id) }}"
                                       class="btn btn-action-group btn-group-default"
                                       data-bs-toggle="tooltip"
                                       title="Ver Ficha da Venda">
                                        <i class="bi bi-eye text-secondary"></i>
                                    </a>

                                    <a href="{{ route('sales.edit', $sale->id) }}"
                                       class="btn btn-action-group btn-group-default"
                                       data-bs-toggle="tooltip"
                                       title="Editar Venda">
                                        <i class="bi bi-pencil text-dark"></i>
                                    </a>

                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-action-group btn-group-danger btn-group-default"
                                                onclick="return confirm('Tem a certeza que deseja enviar esta venda para a reciclagem?')"
                                                data-bs-toggle="tooltip"
                                                title="Eliminar Venda">
                                            <i class="bi bi-trash3 text-danger"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Não existem vendas registadas no sistema.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    html { overflow-y: scroll; }

    /* Sombra mais pronunciada e realista (Efeito de elevação 3D subtil) */
    .shadow-premium {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.09), 0 1px 4px rgba(0, 0, 0, 0.04) !important;
        border-radius: 20px;
        display: inline-flex;
    }

    /* Sombra mais suave e arredondada para botões isolados (Squircle) */
    .shadow-premium-sm {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.02) !important;
    }

    /* Cada botão ficou 30% mais largo (de 36px passou para ~47px) */
    .btn-action-group {
        width: 47px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        transition: all 0.15s ease-in-out;
        margin: 0;
    }

    /* Botão Único (Squircle) com formato idêntico às ações da direita */
    .btn-squircle-view {
        width: 36px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        background-color: #ffffff !important;
        border-radius: 8px; /* Cria o efeito squircle */
        transition: all 0.15s ease-in-out;
    }
    .btn-squircle-view:hover {
        background-color: #f8f9fa !important;
        border-color: #cbd5e1;
    }

    /* Ícones mantêm o tamanho realçado */
    .btn-action-group i, .btn-squircle-view i {
        font-size: 1.05rem;
        line-height: 1;
    }

    /* Forçar a aparição da linha divisória entre TODOS os botões */
    .btn-action-group:not(:first-child) {
        border-left: 1px solid #e2e8f0 !important;
    }

    /* Cantos arredondados estilo Baguette */
    .btn-group > .btn-action-group:first-child {
        border-top-left-radius: 20px !important;
        border-bottom-left-radius: 20px !important;
    }
    .btn-group > form:last-child .btn-action-group {
        border-top-right-radius: 20px !important;
        border-bottom-right-radius: 20px !important;
    }

    /* VARIANTES DE FUNDO */

    /* 1. Fundo Padrão (Branco) */
    .btn-group-default {
        background-color: #ffffff !important;
    }
    .btn-group-default:hover {
        background-color: #f8f9fa !important;
    }

    /* 2. Comportamento do botão de lixo ao fazer hover */
    .btn-group-danger:hover {
        background-color: rgba(220, 53, 69, 0.08) !important;
    }
</style>
@endsection
