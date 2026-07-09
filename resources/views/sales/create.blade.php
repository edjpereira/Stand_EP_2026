@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registar Nova Venda</h2>
    <hr>
    <form action="{{ route('sales.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Cliente</label>
                <select name="client_id" class="form-select @error('client_id') is-invalid @enderror">
                    <option value="">-- Selecione o Cliente --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }} (NIF: {{ $client->tax_id }})
                        </option>
                    @endforeach
                </select>
                @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Viatura (Apenas Disponíveis)</label>
                <select name="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror">
                    <option value="">-- Selecione a Viatura --</option>
                    @foreach($vehicles as $vehicle)
                        {{-- Verifica primeiro o old(), depois se é o ID vindo do botão da Show --}}
                        <option value="{{ $vehicle->id }}" {{ (old('vehicle_id', $selectedVehicleId) == $vehicle->id) ? 'selected' : '' }}>
                            {{ $vehicle->make }} {{ $vehicle->model }} - [{{ $vehicle->plate }}] ({{ number_format($vehicle->price, 2, ',', '.') }} €)
                        </option>
                    @endforeach
                </select>
                @error('vehicle_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Data da Venda</label>
                <input type="date" name="sale_date" class="form-control @error('sale_date') is-invalid @enderror" value="{{ old('sale_date', date('Y-m-d')) }}">
                @error('sale_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Valor Final da Venda (€)</label>
                <input type="number" step="0.01" name="sale_amount" class="form-control @error('sale_amount') is-invalid @enderror" value="{{ old('sale_amount') }}" placeholder="0.00">
                @error('sale_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Observações / Notas de Venda</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Ex: Desconto de campanha incluído, garantia de 2 anos...">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Concluir Venda</button>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
