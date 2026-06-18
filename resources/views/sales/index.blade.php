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
                                <a href="{{ route('vehicles.show', $sale->vehicle->id) }}" class="text-decoration-none">
                                    <span class="badge bg-light text-dark border border-primary target-badge">{{ $sale->vehicle->plate }}</span>
                                </a>
                            </td>

                            <td>
                                <strong>
                                    {{ number_format($sale->price > 0 ? $sale->price : $sale->vehicle->price, 2, ',', '.') }} €
                                </strong>
                            </td>
                            <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>

                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info text-white">Ver</a>

                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-warning text-white">Editar</a>
                                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja anular esta venda?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Anular</button>
                                        </form>
                                    @endif
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
    .link-hover:hover {
        text-decoration: underline !important;
        color: #0d6efd !important;
    }
    .target-badge:hover {
        background-color: #e9ecef !important;
        border-color: #0a58ca !important;
    }
</style>
@endsection
