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

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID Venda</th>
                    <th>Cliente</th>
                    <th>Viatura</th>
                    <th>Data</th>
                    <th>Valor da Venda</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td>#{{ $sale->id }}</td>
                    <td>{{ $sale->client->name }}</td>
                    <td><strong>{{ $sale->vehicle->make }} {{ $sale->vehicle->model }}</strong> ({{ $sale->vehicle->plate }})</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
                    <td><strong>{{ number_format($sale->sale_amount, 2, ',', '.') }} €</strong></td>
                    <td>
                        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info text-white">Detalhes</a>

                        @if(auth()->user()->role === 'admin')
                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ao anular a venda, o carro voltará a ficar Disponível. Continuar?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Anular</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
