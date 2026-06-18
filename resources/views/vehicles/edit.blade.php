@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Viatura: {{ $vehicle->make }} {{ $vehicle->model }} - {{ $vehicle->plate }}</h2>
    <hr>
    <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Linha 1: Marca, Modelo e Matrícula --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Marca</label>
                <input type="text" name="make" class="form-control @error('make') is-invalid @enderror" value="{{ old('make', $vehicle->make) }}">
                @error('make') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Modelo</label>
                <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model', $vehicle->model) }}">
                @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Matrícula</label>
                <input type="text" name="plate" class="form-control @error('plate') is-invalid @enderror" value="{{ old('plate', $vehicle->plate) }}">
                @error('plate') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Linha 2: Ano, Quilómetros, Preço e Estado --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <label class="form-label">Ano</label>
                <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', $vehicle->year) }}">
                @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Quilómetros</label>
                <input type="number" name="mileage" class="form-control @error('mileage') is-invalid @enderror" value="{{ old('mileage', $vehicle->mileage) }}">
                @error('mileage') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Preço (€)</label>
                <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $vehicle->price) }}">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="available" {{ old('status', $vehicle->status) == 'available' ? 'selected' : '' }}>Disponível</option>
                    <option value="sold" {{ old('status', $vehicle->status) == 'sold' ? 'selected' : '' }}>Vendido</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Botões de Ação --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-success px-4">Atualizar Viatura</button>
            <a href="{{ route('vehicles.index') }}" class="btn btn-secondary px-4">Cancelar</a>
        </div>
    </form>
</div>
@endsection
