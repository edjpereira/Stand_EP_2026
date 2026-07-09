@extends('layouts.app')

@section('content')
<div class="container pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Painel de Gestão de Eliminações</h2>
        <a href="{{ route('home_dashboard') }}" class="btn btn-primary">Voltar ao Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- SECÇÃO: CLIENTES ELIMINADOS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Pedidos de Eliminação: Clientes</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="table-layout: fixed; width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50%;">Nome</th>
                            <th style="width: 25%;">NIF</th>
                            <th class="text-center" style="width: 25%;">Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deletedClients as $client)
                            {{-- Chama a parcial do cliente --}}
                            @include('clients.partials.trash_row', ['client' => $client])
                        @empty
                            <tr><td colspan="3" class="text-center py-3 text-muted">Sem pedidos pendentes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SECÇÃO: VIATURAS ELIMINADAS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0"><i class="bi bi-car-front-fill me-2"></i>Pedidos de Eliminação: Viaturas</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="table-layout: fixed; width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50%;">Veículo</th>
                            <th style="width: 25%;">Matrícula</th>
                            <th class="text-center" style="width: 25%;">Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deletedVehicles as $vehicle)
                            @include('vehicles.partials.trash_row', ['vehicle' => $vehicle])
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
    body.modal-open { padding-right: 0px !important; }
    .modal { padding-right: 0px !important; }
</style>
@endsection
