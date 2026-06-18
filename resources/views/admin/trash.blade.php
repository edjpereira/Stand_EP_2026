@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Painel de Gestão de Eliminações</h2>
        <a href="{{ route('home') }}" class="btn btn-primary">Voltar ao Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-people-fill me-2"></i>Pedidos de Eliminação: Clientes
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>NIF</th>
                            <th class="text-end">Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deletedClients as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->taxId }}</td>
                            <td class="text-end">
                                <form action="{{ route('clients.restore', $client->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-success">Restaurar</button>
                                </form>
                                <form action="{{ route('clients.force', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apagar definitivamente?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Apagar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-3 text-muted">Sem pedidos pendentes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-car-front-fill"></i> Pedidos de Eliminação: Viaturas
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Veículo</th>
                            <th>Matrícula</th>
                            <th class="text-end">Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deletedVehicles as $vehicle)
                        <tr>
                            <td>{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                            <td>{{ $vehicle->plate }}</td>
                            <td class="text-end">
                                <form action="{{ route('vehicles.restore', $vehicle->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-success">Restaurar</button>
                                </form>
                                <form action="{{ route('vehicles.force', $vehicle->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apagar definitivamente?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Apagar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-3 text-muted">Sem pedidos pendentes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    html { overflow-y: scroll; }
</style>
@endsection
