@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Viatura: {{ $vehicle->make }} {{ $vehicle->model }}</h2>
    <hr>
    <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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

        <div class="row mb-3">
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
