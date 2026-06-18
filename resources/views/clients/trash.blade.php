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
                        @forelse($deletedClients as $client)
                        <tr>
                            <td><strong>#{{ $client->id }}</strong></td>
                            <td><strong>{{ $client->name }}</strong></td>
                            <td><span class="badge bg-light text-dark border">{{ $client->taxId ?? 'N/A' }}</span></td>
                            <td>{{ $client->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <form action="{{ route('clients.restore', $client->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success text-white me-1">
                                        Rejeitar Pedido (Restaurar)
                                    </button>
                                </form>

                                <form action="{{ route('clients.force', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ATENÇÃO: Isto irá apagar definitivamente o cliente da base de dados. Continuar?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Aprovar (Eliminar de Vez)
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Não existem pedidos de eliminação pendentes.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
