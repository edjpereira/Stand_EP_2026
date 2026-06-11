@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registar Nova Viatura</h2>
    <hr>
    <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Marca</label>
                <input type="text" name="make" class="form-control @error('make') is-invalid @enderror" value="{{ old('make') }}">
                @error('make') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Modelo</label>
                <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model') }}">
                @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Matrícula</label>
                <input type="text" name="plate" class="form-control @error('plate') is-invalid @enderror" value="{{ old('plate') }}" placeholder="AA-00-AA">
                @error('plate') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Ano</label>
                <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', date('Y')) }}">
                @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Quilómetros</label>
                <input type="number" name="mileage" class="form-control @error('mileage') is-invalid @enderror" value="{{ old('mileage', 0) }}">
                @error('mileage') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Preço (€)</label>
                <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Disponível</option>
                    <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Vendido</option>
                </select>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label">Fotografia da Viatura</label>
            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
            @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-success">Guardar Viatura</button>
        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
