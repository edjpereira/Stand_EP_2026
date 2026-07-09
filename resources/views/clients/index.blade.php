@extends('layouts.app')

@push('styles')
    <style>
        html {
            overflow-y: scroll;
        }

        /* Sombra mais pronunciada e realista (Efeito de elevação 3D subtil) */
        .shadow-premium {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.330), 0 1px 4px rgba(0, 0, 0, 0.330) !important;
            border-radius: 20px;
            display: inline-flex;
        }

        /* Cada botão ficou 30% mais largo */
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

        .btn-action-group i {
            font-size: 1.05rem;
            line-height: 1;
        }

        .btn-action-group:not(:first-child) {
            border-left: 1px solid #e2e8f0 !important;
        }

        .btn-group>.btn-action-group:first-child {
            border-top-left-radius: 20px !important;
            border-bottom-left-radius: 20px !important;
        }

        .btn-group>form:last-child .btn-action-group {
            border-top-right-radius: 20px !important;
            border-bottom-right-radius: 20px !important;
        }

        /* VARIANTES DE FUNDO */
        .btn-group-default {
            background-color: #ffffff !important;
        }

        .btn-group-default:hover {
            background-color: #f8f9fa !important;
        }

        .btn-group-success {
            background-color: #19a868 !important;
            border-color: #19a868 !important;
        }

        .btn-group-success:hover {
            background-color: #158f58 !important;
            border-color: #158f58 !important;
        }

        .btn-group-danger:hover {
            background-color: rgba(220, 53, 69, 0.08) !important;
        }
    </style>
@endpush

@section('content')
    <div class="container pt-4">
        {{-- Cabeçalho Principal --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0 text-dark fw-bold">Gestão de Clientes</h2>
            <a href="{{ route('clients.create') }}" class="btn btn-success shadow-sm">
                <i class="bi bi-person-plus me-1"></i> Adicionar Cliente
            </a>
        </div>

        {{-- Alertas de Feedback --}}
        @if (session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
        @endif

        {{-- Barra de Pesquisa e Filtros --}}
        <form method="GET" action="{{ route('clients.index') }}" class="mb-4">
            <div class="card shadow-sm border border-light-subtle rounded-3 p-3 bg-white">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Pesquisa Geral</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Nome ou telefone..."
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">NIF / Contribuinte</label>
                        <input type="text" name="tax_id" class="form-control"
                            placeholder="Ex: 577615979"
                            value="{{ request('tax_id') }}" max="9">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">Filtros rápidos</label>
                        <select name="filter_status" class="form-select">
                            <option value="">Todos os clientes</option>
                            <option value="has_sales" {{ request('filter_status') == 'has_sales' ? 'selected' : '' }}>Com vendas</option>
                            <option value="has_interactions" {{ request('filter_status') == 'has_interactions' ? 'selected' : '' }}>Com interacções</option>
                            <option value="no_interactions" {{ request('filter_status') == 'no_interactions' ? 'selected' : '' }}>Sem interacções</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">Ordenar por</label>
                        <select name="sort_by" class="form-select">
                            {{-- Define 'id' como o valor padrão caso não haja request --}}
                            <option value="id" {{ request('sort_by', 'id') == 'id' ? 'selected' : '' }}>ID de Registo</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nome</option>
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Data de Criação</option>
                        </select>
                    </div>

                    <div class="col-md-1">
                        <label class="form-label small fw-bold text-muted mb-1">Ordem</label>
                        <select name="sort_order" class="form-select">
                            {{-- Define 'asc' como o valor padrão caso não haja request --}}
                            <option value="asc" {{ request('sort_order', 'asc') == 'asc' ? 'selected' : '' }}>↑ Asc</option>
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>↓ Dsc</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1" data-bs-toggle="tooltip" title="Aplicar Filtros">
                            <i class="bi bi-filter"></i> Filtrar
                        </button>

                        @if (request('search') || request('tax_id') || request('filter_status') || request('sort_by') || request('sort_order'))
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Limpar Filtros">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>

        {{-- Tabela de Clientes --}}
        <div class="card shadow-sm border border-light-subtle rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Morada</th>
                                <th>NIF</th>
                                <th class="text-center pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
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
                                            // Carrega a contagem de vendas protegida contra N+1
                                            $hasSales = $client->sales_count > 0;

                                            // Botões padrão assumem sempre a classe default (Branco / Hover cinza suave)
                                            $bgClass = 'btn-group-default';
                                        @endphp

                                        <div class="btn-group shadow-premium" role="group" aria-label="Ações de Cliente">

                                            {{-- 1. Botão Visualizar (Verde se tiver vendas, caso contrário segue o $bgClass padrão) --}}
                                            @if ($hasSales)
                                                <a href="{{ route('clients.show', $client->id) }}"
                                                    class="btn btn-action-group btn-group-success" data-bs-toggle="tooltip"
                                                    title="Ver Ficha (Cliente Comprador)">
                                                    <i class="bi bi-eye-fill text-white"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('clients.show', $client->id) }}"
                                                    class="btn btn-action-group {{ $bgClass }}"
                                                    data-bs-toggle="tooltip"
                                                    title="Ver Ficha">
                                                    <i class="bi bi-eye text-secondary"></i>
                                                </a>
                                            @endif

                                            {{-- 2. Botão Editar --}}
                                            <a href="{{ route('clients.edit', $client->id) }}"
                                                class="btn btn-action-group {{ $bgClass }}" data-bs-toggle="tooltip"
                                                title="Editar Cliente">
                                                <i class="bi bi-pencil text-dark"></i>
                                            </a>

                                            {{-- 3. Botão Eliminar --}}
                                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-action-group btn-group-danger {{ $bgClass }}"
                                                    onclick="return confirm('Tem a certeza que deseja enviar este cliente para a reciclagem?')"
                                                    data-bs-toggle="tooltip" title="Eliminar Cliente">
                                                    <i class="bi bi-trash3 text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- Estado Vazio Inteligente --}}
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-people fs-2 text-muted opacity-50 d-block mb-2"></i>
                                        @if(request()->anyFilled(['search', 'tax_id', 'filter_status']))
                                            <h5 class="fw-semibold h6 text-dark mb-1">Nenhum resultado encontrado</h5>
                                            <span class="small">Experimenta ajustar ou limpar os filtros de pesquisa atuais.</span>
                                        @else
                                            <span class="small">Não existem clientes registados no sistema.</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Paginação --}}
        @if ($clients->hasPages() || $clients->total() > 0)
            <div class="mt-4">
                <div class="d-flex justify-content-center">
                    {{ $clients->links('pagination::bootstrap-5') }}
                </div>
                <p class="text-center text-muted small mt-2">
                    A mostrar <strong>{{ $clients->firstItem() ?? 0 }}</strong> a
                    <strong>{{ $clients->lastItem() ?? 0 }}</strong> de <strong>{{ $clients->total() }}</strong> registos.
                </p>
            </div>
        @endif
    </div>
@endsection
