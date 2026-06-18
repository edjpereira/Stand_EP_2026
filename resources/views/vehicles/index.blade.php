@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Stock de Viaturas</h2>
            <a href="{{ route('vehicles.create') }}" class="btn btn-success">Adicionar Viatura</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <div class="card-body bg-light border-bottom d-print-none">
                        <form action="{{ route('vehicles.index') }}" method="GET" class="row g-3 align-items-center">

                            {{-- Campo de Pesquisa --}}
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Pesquise por Marca, Modelo ou Matrícula..."
                                        value="{{ request('search') }}">
                                    @if (request('search'))
                                        <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">Limpar</a>
                                    @endif
                                </div>
                            </div>

                            {{-- Ordenar Por --}}
                            <div class="col-md-3">
                                <select name="sort_by" class="form-select">
                                    <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>Ordenar por:
                                        Mais Recentes</option>
                                    <option value="make" {{ request('sort_by') == 'make' ? 'selected' : '' }}>Ordenar por:
                                        Marca</option>
                                    <option value="model" {{ request('sort_by') == 'model' ? 'selected' : '' }}>Ordenar
                                        por: Modelo</option>
                                    <option value="year" {{ request('sort_by') == 'year' ? 'selected' : '' }}>Ordenar por:
                                        Ano</option>
                                    <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Ordenar
                                        por: Preço</option>
                                </select>
                            </div>

                            {{-- Direção da Ordenação (Ascendente / Descendente) --}}
                            <div class="col-md-2">
                                <select name="sort_order" class="form-select">
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Crescente
                                        ↑</option>
                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>
                                        Decrescente ↓</option>
                                </select>
                            </div>

                            {{-- Botão de Submissão --}}
                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-filter"></i> Aplicar
                                </button>
                            </div>

                        </form>
                    </div>
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Foto</th>
                                <th>Marca / Modelo</th>
                                <th class="text-center">Matrícula</th>
                                <th>Ano</th>
                                <th>Quilómetros</th>
                                <th>Preço</th>
                                <th>Estado</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicles as $vehicle)
                                <tr>
                                    <td>
                                        @if ($vehicle->image)
                                            <img src="{{ asset('storage/' . $vehicle->image) }}" alt="Foto"
                                                class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                                        @else
                                            <span class="badge bg-secondary">Sem Foto</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $vehicle->make }}</strong> {{ $vehicle->model }}</td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex align-items-center justify-content-center">
                                            {{-- Componente da Matrícula Realista --}}
                                            <div class="eu-plate shadow-sm">
                                                <span class="plate-text">{{ $vehicle->plate }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $vehicle->year }}</td>
                                    <td>{{ number_format($vehicle->mileage, 0, ',', '.') }} km</td>
                                    <td><strong>{{ number_format($vehicle->price, 2, ',', '.') }} €</strong></td>
                                    <td>
                                        @if ($vehicle->status === 'available')
                                            <span class="badge bg-success">Disponível</span>
                                        @elseif($vehicle->status === 'sold')
                                            <span class="badge bg-danger">Vendido</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ ucfirst($vehicle->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end text-nowrap align-middle" style="width: 160px;">
                                        {{-- Grupo de botões unificado estilo Baguette para Viaturas --}}
                                        <div class="btn-group shadow-premium-vehicle" role="group"
                                            aria-label="Ações de Viatura">

                                            {{-- 1. Botão Visualizar (Olho) --}}
                                            <a href="{{ route('vehicles.show', $vehicle->id) }}"
                                                class="btn btn-vehicle-group" data-bs-toggle="tooltip"
                                                title="Ver Detalhes da Viatura">
                                                <i class="bi bi-eye text-secondary"></i>
                                            </a>

                                            {{-- 2. Botão Editar (Lápis) --}}
                                            <a href="{{ route('vehicles.edit', $vehicle->id) }}"
                                                class="btn btn-vehicle-group" data-bs-toggle="tooltip"
                                                title="Editar Viatura">
                                                <i class="bi bi-pencil text-dark"></i>
                                            </a>

                                            {{-- 3. Botão Eliminar (Cesto do Lixo) --}}
                                            <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-vehicle-group btn-vehicle-danger"
                                                    onclick="return confirm('Tem a certeza que deseja enviar esta viatura para a reciclagem?')"
                                                    data-bs-toggle="tooltip" title="Eliminar Viatura">
                                                    <i class="bi bi-trash3 text-danger"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        Não existem viaturas registadas no stock.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if ($vehicles->hasPages() || $vehicles->total() > 0)
            <div class="mt-4 text-center">
                <p class="text-muted small mb-2">
                    A mostrar <strong>{{ $vehicles->firstItem() ?? 0 }}</strong> a
                    <strong>{{ $vehicles->lastItem() ?? 0 }}</strong> de um total de
                    <strong>{{ $vehicles->total() }}</strong> viaturas
                </p>

                <div class="d-flex justify-content-center">
                    <ul class="pagination mb-0">
                        @if ($vehicles->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $vehicles->previousPageUrl() }}"
                                    rel="prev">Anterior</a></li>
                        @endif

                        @foreach ($vehicles->getUrlRange(max(1, $vehicles->currentPage() - 2), min($vehicles->lastPage(), $vehicles->currentPage() + 2)) as $page => $url)
                            @if ($page == $vehicles->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link"
                                        href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        @if ($vehicles->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $vehicles->nextPageUrl() }}"
                                    rel="next">Seguinte</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">Seguinte</span></li>
                        @endif
                    </ul>
                </div>
            </div>
        @endif
    </div>
    <style>
        /* Sombra realista e pronunciada */
        .shadow-premium-vehicle {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.09), 0 1px 4px rgba(0, 0, 0, 0.04) !important;
            border-radius: 20px;
            display: inline-flex;
        }

        /* Largura de 47px (+30%) e fundo branco padrão */
        .btn-vehicle-group {
            width: 47px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background-color: #ffffff !important;
            transition: all 0.15s ease-in-out;
            margin: 0;
        }

        /* Tamanho realçado dos ícones */
        .btn-vehicle-group i {
            font-size: 1.05rem;
            line-height: 1;
        }

        /* Forçar linha divisória entre todos os botões */
        .btn-vehicle-group:not(:first-child) {
            border-left: 1px solid #e2e8f0 !important;
        }

        /* Efeito hover suave nos botões neutros */
        .btn-vehicle-group:hover {
            background-color: #f8f9fa !important;
        }

        /* Hover dedicado para o botão de apagar */
        .btn-vehicle-danger:hover {
            background-color: rgba(220, 53, 69, 0.08) !important;
        }

        .btn-vehicle-danger:hover i {
            color: #bb2d3b !important;
        }

        /* Arredondamento perfeito das pontas (Baguette) */
        .btn-group>.btn-vehicle-group:first-child {
            border-top-left-radius: 20px !important;
            border-bottom-left-radius: 20px !important;
        }

        .btn-group>form:last-child .btn-vehicle-group {
            border-top-right-radius: 20px !important;
            border-bottom-right-radius: 20px !important;
        }
        .eu-plate {
        /* Largura fixa baseada na ocupação máxima (ex: OO-00-OO) */
        width: 125px;
        height: 28px;
        background-color: #ffffff;

        /* Desenha a faixa azul vertical exatamente nos primeiros 5% à esquerda */
        background-image: linear-gradient(to right, #003399 10%, #ffffff 5%);

        /* Bordas e aspeto de chapa metálica */
        border: 1.5px solid #212529;
        border-radius: 4px;

        /* Flexbox para alinhar o texto nos 95% restantes */
        display: inline-flex;
        align-items: center;
        justify-content: flex-end; /* Empurra o conteúdo para a direita */
        position: relative;
        box-sizing: border-box;
    }

    .plate-text {
        /* Ocupa exatamente a zona branca (90% da largura) */
        width: 90%;
        text-align: center;

        /* Estilo dos caracteres da matrícula */
        font-family: 'Courier New', Courier, monospace; /* Fonte mono-espaçada simula chapas reais */
        font-weight: 900;
        font-size: 0.95rem;
        color: #111111;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding-right: 2px; /* Pequeno ajuste fino de centralização */
    }
    </style>
@endsection
