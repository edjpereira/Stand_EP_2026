@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalhes da Venda #{{ $sale->id }}</h2>
    <hr>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Resumo da Transação</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 border-end">
                    <h5><i class="bi bi-person"></i> Dados do Cliente</h5>
                    <p class="mb-1"><strong>Nome:</strong> {{ $sale->client->name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $sale->client->email }}</p>
                    <p class="mb-1"><strong>NIF:</strong> {{ $sale->client->tax_id }}</p>
                    <p class="mb-1"><strong>Contacto:</strong> {{ $sale->client->phone }}</p>
                </div>
                <div class="col-md-6 ps-4">
                    <h5><i class="bi bi-car-front"></i> Dados da Viatura</h5>
                    <p class="mb-1"><strong>Viatura:</strong> {{ $sale->vehicle->make }} {{ $sale->vehicle->model }}</p>
                    <p class="mb-1"><strong>Matrícula:</strong> {{ $sale->vehicle->plate }}</p>
                    <p class="mb-1"><strong>Ano / KM:</strong> {{ $sale->vehicle->year }} | {{ number_format($sale->vehicle->mileage, 0, ',', '.') }} km</p>
                    <p class="mb-1"><strong>Preço de Tabela:</strong> {{ number_format($sale->vehicle->price, 2, ',', '.') }} €</p>
                </div>
            </div>
            <hr>
            <div class="row bg-light p-3 rounded">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Data de Venda:</strong> {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</p>
                    <p class="mb-0"><strong>Observações:</strong> {{ $sale->notes ?? 'Sem observações registadas.' }}</p>
                </div>
                <div class="col-md-6 text-md-end d-flex flex-column justify-content-center">
                    <span class="text-muted small">Valor de Fecho</span>
                    <h3 class="text-success mb-0"><strong>{{ number_format($sale->sale_amount, 2, ',', '.') }} €</strong></h3>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">Voltar às Vendas</a>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning text-white">Editar Venda</a>

                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja eliminar permanentemente esta venda?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar Registo</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
