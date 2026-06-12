@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Venda #{{ $sale->id }}</h2>
    <hr>
    <div class="alert alert-warning">
        <strong>Atenção:</strong> Está a alterar um registo de venda ativo. A viatura associada é a <strong>{{ $sale->vehicle->make }} {{ $sale->vehicle->model }} [{{ $sale->vehicle->plate }}]</strong>.
    </div>

    <form action="{{ route('sales.update', $sale->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Cliente</label>
                <select name="client_id" class="form-select @error('client_id') is-invalid @enderror">
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $sale->client_id) == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
                @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Data da Venda</label>
                <input type="date" name="sale_date" class="form-control @error('sale_date') is-invalid @enderror" value="{{ old('sale_date', $sale->sale_date) }}">
                @error('sale_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Valor da Venda (€)</label>
                <input type="number" step="0.01" name="sale_amount" class="form-control @error('sale_amount') is-invalid @enderror" value="{{ old('sale_amount', $sale->sale_amount) }}">
                @error('sale_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Observações / Notas</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $sale->notes) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Dados</button>
        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
