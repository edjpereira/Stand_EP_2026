@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalhes da Viatura</h2>
    <hr>
    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-3" style="min-height: 250px;">
                @if($vehicle->photo)
                    <img src="{{ asset('storage/' . $vehicle->photo) }}" alt="Foto da Viatura" class="img-fluid rounded shadow-sm" style="max-height: 280px; object-fit: cover;">
                @else
                    <div class="text-center text-muted">
                        <i class="bi bi-car-front" style="font-size: 3rem;"></i>
                        <p class="mt-2 mb-0">Sem fotografia disponível</p>
                    </div>
                @endif
            </div>
            <div class="col-md-7">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $vehicle->make }} {{ $vehicle->model }}</h4>
                    <span class="badge {{ $vehicle->status == 'available' ? 'bg-success' : 'bg-danger' }} fs-6">
                        {{ $vehicle->status == 'available' ? 'Disponível' : 'Vendido' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row bg-light p-2 rounded mb-3 text-center">
                        <div class="col-4 border-end">
                            <small class="text-muted d-block">Ano</small>
                            <strong>{{ $vehicle->year }}</strong>
                        </div>
                        <div class="col-4 border-end">
                            <small class="text-muted d-block">Quilómetros</small>
                            <strong>{{ number_format($vehicle->mileage, 0, ',', '.') }} km</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Preço</small>
                            <strong class="text-success">{{ number_format($vehicle->price, 2, ',', '.') }} €</strong>
                        </div>
                    </div>

                    <p><strong>ID do Registo:</strong> #{{ $vehicle->id }}</p>
                    <p><strong>Matrícula:</strong> <span class="badge bg-secondary fs-6">{{ $vehicle->plate }}</span></p>
                    <p><strong>Data de Entrada no Sistema:</strong> {{ $vehicle->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="card-footer bg-transparent border-top-0 pb-3">
                    <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-warning text-white">Editar Dados</a>
                    <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Voltar ao Stock</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
