@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Pedidos de Eliminação de Clientes</h2>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Voltar à Listagem</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>NIF</th>
                            <th>Data do Pedido</th>
                            <th class="text-center">Ações / Decisão</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trashedClients as $client)
                            <tr>
                                <td class="ps-4"><strong>#{{ $client->id }}</strong></td>
                                <td><strong>{{ $client->name }}</strong></td>
                                <td><a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a></td>
                                <td>{{ $client->phone ?? '---' }}</td>
                                <td class="text-truncate" style="max-width: 200px;">{{ $client->address ?? '---' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $client->taxId ?? 'N/A' }}</span>
                                </td>
                                <td class="text-end text-nowrap pe-4" style="width: 160px;">
                                    @php
                                        $bgClass = 'btn-group-default';
                                    @endphp

                                    <div class="btn-group shadow-premium" role="group" aria-label="Ações de Reciclagem">

                                        {{-- 1. NOVO: Botão Visualizar em Modal (Cinzento, na Esquerda) --}}
                                        <button type="button"
                                            class="btn btn-action-group {{ $bgClass }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewClientModal{{ $client->id }}"
                                            title="Espreitar Ficha">
                                            <i class="bi bi-eye text-secondary"></i>
                                        </button>

                                        {{-- 2. Botão Restaurar (Meio) --}}
                                        <form action="{{ route('clients.restore', $client->id) }}" method="POST" class="d-inline m-0 p-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-action-group {{ $bgClass }}" data-bs-toggle="tooltip" title="Restaurar Cliente">
                                                <i class="bi bi-arrow-counterclockwise text-success"></i>
                                            </button>
                                        </form>

                                        {{-- 3. Botão Eliminar Permanentemente (Direita) --}}
                                        <form action="{{ route('clients.forceDelete', $client->id) }}" method="POST" class="d-inline m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-action-group btn-group-danger {{ $bgClass }}"
                                                onclick="return confirm('Atenção: Esta ação é irreversível. Desejas eliminar permanentemente este cliente?')"
                                                data-bs-toggle="tooltip" title="Eliminar Permanentemente">
                                                <i class="bi bi-trash3 text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-trash-text fs-2 text-muted opacity-50 d-block mb-2"></i>
                                    A reciclagem está vazia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Modals de Visualização Rápida --}}
@foreach($trashedClients as $client)
    <div class="modal fade" id="viewClientModal{{ $client->id }}" tabindex="-1" aria-labelledby="viewClientModalLabel{{ $client->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow border-0">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="viewClientModalLabel{{ $client->id }}">
                        <i class="bi bi-person-bounding-box me-2 text-secondary"></i> Ficha de Cliente (Reciclado)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="text-center mb-3">
                        <div class="bg-light d-inline-block rounded-circle p-3 mb-2">
                            <i class="bi bi-person text-secondary h3 m-0"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $client->name }}</h4>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 mt-1">ID #{{ $client->id }} — Na Reciclagem</span>
                    </div>

                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted fw-semibold">E-mail:</span>
                            <span class="text-dark fw-bold">{{ $client->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted fw-semibold">Telefone:</span>
                            <span class="text-dark fw-bold">{{ $client->phone ?? '---' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted fw-semibold">NIF / Contribuinte:</span>
                            <span class="badge bg-light text-dark border fw-bold">{{ $client->taxId ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex flex-column align-items-start px-0 py-2">
                            <span class="text-muted fw-semibold mb-1">Morada:</span>
                            <span class="text-dark fw-medium">{{ $client->address ?? 'Nenhuma morada registada.' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted fw-semibold">Data de Remoção:</span>
                            <span class="text-danger fw-bold">{{ $client->deleted_at ? $client->deleted_at->format('d/m/Y H:i') : '---' }}</span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer border-top-0 pt-0 justify-content-end">
                    <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
