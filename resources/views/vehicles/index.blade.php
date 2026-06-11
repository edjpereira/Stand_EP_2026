@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Stock de Viaturas</h2>
        <a href="{{ route('vehicles.create') }}" class="btn btn-primary">Adicionar Viatura</a>
    </div>

    <div class="card mb-4 bg-light">
        <div class="card-body">
            <form action="{{ route('vehicles.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Pesquisar por Marca, Modelo ou Matrícula..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="sort_by" class="form-select">
                        <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>Ordenar por ID</option>
                        <option value="make" {{ request('sort_by') == 'make' ? 'selected' : '' }}>Ordenar por Marca</option>
                        <option value="model" {{ request('sort_by') == 'model' ? 'selected' : '' }}>Ordenar por Modelo</option>
                        <option value="year" {{ request('sort_by') == 'year' ? 'selected' : '' }}>Ordenar por Ano</option>
                        <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Ordenar por Preço</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort_order" class="form-select">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Crescente (A-Z / Min-Max)</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Decrescente (Z-A / Max-Min)</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-dark">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Foto</th>
                    <th>ID</th>
                    <th>Marca / Modelo</th>
                    <th>Matrícula</th>
                    <th>Ano</th>
                    <th>KM</th>
                    <th>Preço</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $vehicle)
                <tr>
                    <td>
                        @if($vehicle->photo)
                            <img src="{{ asset('storage/' . $vehicle->photo) }}" alt="Foto" style="width: 60px; height: 40px; object-fit: cover;" class="rounded">
                        @else
                            <span class="text-muted" style="font-size: 12px;">Sem foto</span>
                        @endif
                    </td>
                    <td>{{ $vehicle->id }}</td>
                    <td><strong>{{ $vehicle->make }}</strong> {{ $vehicle->model }}</td>
                    <td><span class="badge bg-secondary">{{ $vehicle->plate }}</span></td>
                    <td>{{ $vehicle->year }}</td>
                    <td>{{ number_format($vehicle->mileage, 0, ',', '.') }} km</td>
                    <td><strong>{{ number_format($vehicle->price, 2, ',', '.') }} €</strong></td>
                    <td>
                        <span class="badge {{ $vehicle->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                            {{ $vehicle->status == 'available' ? 'Disponível' : 'Vendido' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-sm btn-info text-white">Ver</a>
                        <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-warning text-white">Editar</a>
                        <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apagar esta viatura?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Apagar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
