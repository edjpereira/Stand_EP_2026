@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestão de Clientes</h2>
        <a href="{{ route('clients.create') }}" class="btn btn-success">Adicionar Cliente</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Morada</th>
                            <th>NIF</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                        {{-- Atribuímos o data-id para identificação precisa no JS --}}
                        <tr>
                            <td><strong>#{{ $client->id }}</strong></td>
                            <td><strong>{{ $client->name }}</strong></td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->phone ?? '---' }}</td>
                            <td>{{ $client->address ?? '---' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $client->taxId ?? 'N/A' }}</span>
                            </td>
                            <td class="text-end text-nowrap align-middle" style="width: 160px;">
                                @php
                                    // Estados do cliente para decisão de design
                                    $hasSales = ($client->sales_count > 0 || $client->sales->count() > 0);
                                    $hasInteractions = ($client->interactions_count > 0 || $client->interactions->count() > 0);

                                    // Define a classe de fundo do botão 'Ver' e 'Editar' com base nas regras
                                    $bgClass = $hasInteractions && !$hasSales ? 'btn-group-has-interaction' : 'btn-group-default';
                                @endphp

                                {{-- Grupo de botões unificado com sombra mais pronunciada --}}
                                <div class="btn-group shadow-premium" role="group" aria-label="Ações de Cliente">

                                    {{-- 1. Botão Visualizar (Dinâmico) --}}
                                    @if($hasSales)
                                        {{-- Comprador: Fundo Verde Claro, Ícone Branco --}}
                                        <a href="{{ route('clients.show', $client->id) }}"
                                           class="btn btn-action-group btn-group-success"
                                           data-bs-toggle="tooltip"
                                           title="Ver Ficha (Cliente Comprador)">
                                            <i class="bi bi-eye-fill text-white"></i>
                                        </a>
                                    @else
                                        {{-- Sem Vendas: Fundo depende de ter interações. Tooltip (Lead) só aparece se tiver interações --}}
                                        <a href="{{ route('clients.show', $client->id) }}"
                                           class="btn btn-action-group {{ $bgClass }}"
                                           data-bs-toggle="tooltip"
                                           title="Ver Ficha{{ $hasInteractions ? ' (Lead)' : '' }}">
                                            <i class="bi bi-eye text-secondary"></i>
                                        </a>
                                    @endif

                                    {{-- 2. Botão Editar --}}
                                    <a href="{{ route('clients.edit', $client->id) }}"
                                       class="btn btn-action-group {{ $bgClass }}"
                                       data-bs-toggle="tooltip"
                                       title="Editar Cliente">
                                        <i class="bi bi-pencil text-dark"></i>
                                    </a>

                                    {{-- 3. Botão Eliminar (Cesto do Lixo) --}}
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-action-group btn-group-danger {{ $bgClass }}"
                                                onclick="return confirm('Tem a certeza que deseja enviar este cliente para a reciclagem?')"
                                                data-bs-toggle="tooltip"
                                                title="Eliminar Cliente">
                                            <i class="bi bi-trash3 text-danger"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Não existem clientes registados no sistema.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($clients->hasPages() || $clients->total() > 0)
        <div class="mt-4 text-center">
            <p class="text-muted small mb-2">
                A mostrar <strong>{{ $clients->firstItem() ?? 0 }}</strong> a <strong>{{ $clients->lastItem() ?? 0 }}</strong> de um total de <strong>{{ $clients->total() }}</strong> clientes
            </p>

            <div class="d-flex justify-content-center">
                <ul class="pagination mb-0">
                    @if ($clients->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $clients->previousPageUrl() }}" rel="prev">Anterior</a></li>
                    @endif

                    @foreach ($clients->getUrlRange(max(1, $clients->currentPage() - 2), min($clients->lastPage(), $clients->currentPage() + 2)) as $page => $url)
                        @if ($page == $clients->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    @if ($clients->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $clients->nextPageUrl() }}" rel="next">Seguinte</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">Seguinte</span></li>
                    @endif
                </ul>
            </div>
        </div>
    @endif
</div>

<style>
    html { overflow-y: scroll; }

    /* Sombra mais pronunciada e realista (Efeito de elevação 3D subtil) */
    .shadow-premium {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.09), 0 1px 4px rgba(0, 0, 0, 0.04) !important;
        border-radius: 20px;
        display: inline-flex;
    }

    /* Cada botão ficou 30% mais largo (de 36px passou para ~47px) */
    .btn-action-group {
        width: 47px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        transition: all 0.15s ease-in-out;
        margin: 0;
    }

    /* Ícones mantêm o tamanho realçado */
    .btn-action-group i {
        font-size: 1.05rem;
        line-height: 1;
    }

    /* Forçar a aparição da linha divisória entre TODOS os botões */
    .btn-action-group:not(:first-child) {
        border-left: 1px solid #e2e8f0 !important;
    }

    /* Cantos arredondados estilo Baguette */
    .btn-group > .btn-action-group:first-child {
        border-top-left-radius: 20px !important;
        border-bottom-left-radius: 20px !important;
    }
    .btn-group > form:last-child .btn-action-group {
        border-top-right-radius: 20px !important;
        border-bottom-right-radius: 20px !important;
    }

    /* VARIANTES DE FUNDO */

    /* 1. Fundo Padrão (Sem Vendas e Sem Interações) - Branco */
    .btn-group-default {
        background-color: #ffffff !important;
    }
    .btn-group-default:hover {
        background-color: #f8f9fa !important;
    }

    /* 2. Fundo para Clientes com Interação mas sem Vendas - Cinzento Claro */
    .btn-group-has-interaction {
        background-color: #f1f3f5 !important; /* Cinzento suave */
        border-color: #dbe2e8 !important;
    }
    .btn-group-has-interaction:hover {
        background-color: #e9ecef !important;
    }

    /* 3. Botão do Comprador: Verde mais claro e vibrante (Bootstrap Success ligeiramente suavizado) */
    .btn-group-success {
        background-color: #19a868 !important; /* Tom de verde mais claro/aceso */
        border-color: #19a868 !important;
    }
    .btn-group-success:hover {
        background-color: #158f58 !important;
        border-color: #158f58 !important;
    }

    /* 4. Comportamento do botão de lixo ao fazer hover */
    .btn-group-danger:hover {
        background-color: rgba(220, 53, 69, 0.08) !important;
    }
</style>
@endsection
