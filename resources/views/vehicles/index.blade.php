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
                <div class="card-body bg-light border-bottom d-print-none">
                    <form action="{{ route('vehicles.index') }}" method="GET">

                        <div class="row g-3 align-items-center">
                            {{-- Campo de Pesquisa --}}
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Pesquise por Marca, Modelo ou Matrícula..."
                                        value="{{ request('search') }}">
                                    @if (request('search') ||
                                            request('km_min') ||
                                            request('km_max') ||
                                            request('year_min') ||
                                            request('year_max') ||
                                            request('fuel'))
                                        <a href="{{ route('vehicles.index', ['open_advanced' => 1]) }}"
                                            class="btn btn-outline-secondary">Limpar</a>
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

                            <div class="col-md-2">
                                <select name="sort_order" class="form-select">
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Crescente
                                        ↑</option>
                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>
                                        Decrescente ↓</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-filter"></i> Aplicar
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-3 mb-1">
                            <button class="btn btn-link text-decoration-none text-secondary fw-medium p-0 small"
                                type="button" data-bs-toggle="collapse" data-bs-target="#pesquisaAvancadaAdmin"
                                aria-expanded="{{ (request('km_min') !== null && request('km_min') !== '') || (request('km_max') !== null && request('km_max') !== '') || (request('year_min') !== null && request('year_min') !== '') || (request('year_max') !== null && request('year_max') !== '') || (request('fuel') !== null && request('fuel') !== '') || request('open_advanced') ? 'true' : 'false' }}"
                                aria-controls="pesquisaAvancadaAdmin" id="btnPesquisaAvancada">
                                <span class="btn-text">
                                    {{ (request('km_min') !== null && request('km_min') !== '') || (request('km_max') !== null && request('km_max') !== '') || (request('year_min') !== null && request('year_min') !== '') || (request('year_max') !== null && request('year_max') !== '') || (request('fuel') !== null && request('fuel') !== '') || request('open_advanced') ? 'Ocultar' : 'Pesquisa Avançada' }}
                                </span>
                                <i class="bi bi-chevron-right ms-1 transition-icon"></i>
                            </button>
                        </div>

                        <div class="collapse {{ (request('km_min') !== null && request('km_min') !== '') ||
                        (request('km_max') !== null && request('km_max') !== '') ||
                        (request('year_min') !== null && request('year_min') !== '') ||
                        (request('year_max') !== null && request('year_max') !== '') ||
                        (request('fuel') !== null && request('fuel') !== '') ||
                        request('open_advanced')
                            ? 'show'
                            : '' }}"
                            id="pesquisaAvancadaAdmin">
                            <div class="row g-3 pt-2 border-top mt-2">

                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted mb-1">Quilómetros</label>
                                    <div class="input-group input-group-sm">
                                        <select name="km_min" class="form-select">
                                            <option value="">De (km)</option>
                                            <option value="0" {{ request('km_min') === '0' ? 'selected' : '' }}>0 km
                                            </option>
                                            @for ($i = 10000; $i <= 300000; $i += 10000)
                                                <option value="{{ $i }}"
                                                    {{ request('km_min') == $i ? 'selected' : '' }}>
                                                    {{ number_format($i, 0, ',', '.') }} km
                                                </option>
                                            @endfor
                                        </select>
                                        <span class="input-group-text bg-white text-muted">até</span>
                                        <select name="km_max" class="form-select">
                                            <option value="">Até (km)</option>
                                            <option value="0" {{ request('km_max') === '0' ? 'selected' : '' }}>0 km
                                            </option>
                                            @for ($i = 10000; $i <= 290000; $i += 10000)
                                                <option value="{{ $i }}"
                                                    {{ request('km_max') == $i ? 'selected' : '' }}>
                                                    {{ number_format($i, 0, ',', '.') }} km
                                                </option>
                                            @endfor
                                            <option value="300000" {{ request('km_max') == 300000 ? 'selected' : '' }}>
                                                300.000+ km</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted mb-1">Ano</label>
                                    <div class="input-group input-group-sm">

                                        <select name="year_min" class="form-select">
                                            <option value="">De (Ano)</option>
                                            @for ($year = (int) now()->year; $year >= 1990; $year--)
                                                <option value="{{ $year }}"
                                                    {{ request('year_min') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>

                                        <span class="input-group-text bg-white text-muted">até</span>

                                        <select name="year_max" class="form-select">
                                            <option value="">Até (Ano)</option>
                                            @for ($year = (int) now()->year; $year >= 1990; $year--)
                                                <option value="{{ $year }}"
                                                    {{ request('year_max') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>

                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted mb-1">Combustível</label>
                                    <select name="fuel" class="form-select form-select-sm">
                                        <option value="">Todos os tipos</option>
                                        <option value="Gasolina" {{ request('fuel') == 'Gasolina' ? 'selected' : '' }}>
                                            Gasolina</option>
                                        <option value="Diesel" {{ request('fuel') == 'Diesel' ? 'selected' : '' }}>Diesel
                                        </option>
                                        <option value="GPL" {{ request('fuel') == 'GPL' ? 'selected' : '' }}>GPL
                                        </option>
                                        <option value="Eléctrico" {{ request('fuel') == 'Eléctrico' ? 'selected' : '' }}>
                                            Eléctrico</option>
                                        <option value="Híbrido Plug-in"
                                            {{ request('fuel') == 'Híbrido Plug-in' ? 'selected' : '' }}>Híbrido Plug-in
                                        </option>
                                        <option value="Híbrido" {{ request('fuel') == 'Híbrido' ? 'selected' : '' }}>
                                            Híbrido</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                    </form>
                </div>

                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 80px;" class="text-center">Foto</th>
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
                            <tr class="clickable-row" data-href="{{ route('vehicles.show', $vehicle->id) }}">
                                <td class="text-center">
                                    @php
                                        $marcaSlug = strtolower(trim($vehicle->make));
                                        $caminhoFotoMarca = "images/{$marcaSlug}.jpg";
                                        if (!file_exists(public_path($caminhoFotoMarca))) {
                                            $caminhoFotoMarca = 'images/default_car.jpg';
                                        }

                                        $diretorioVeiculo = "vehicles/{$vehicle->id}";
                                        $fotoThumbnail = asset($caminhoFotoMarca);

                                        if (\Storage::disk('public')->exists($diretorioVeiculo)) {
                                            $ficheiros = \Storage::disk('public')->files($diretorioVeiculo);
                                            if (!empty($ficheiros)) {
                                                sort($ficheiros);
                                                $fotoThumbnail = asset('storage/' . $ficheiros[0]);
                                            }
                                        } elseif (
                                            $vehicle->photo &&
                                            \Storage::disk('public')->exists($vehicle->photo)
                                        ) {
                                            $fotoThumbnail = asset('storage/' . $vehicle->photo);
                                        }
                                    @endphp

                                    <div class="thumbnail-wrapper shadow-sm border rounded">
                                        <img src="{{ $fotoThumbnail }}" alt="Thumbnail" class="img-thumbnail-custom">
                                    </div>
                                </td>
                                <td><strong>{{ $vehicle->make }}</strong> {{ $vehicle->model }}</td>

                                <td class="text-center align-middle">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="d-inline-flex align-items-center border border-dark rounded bg-white text-dark font-monospace fw-bold px-2 py-0"
                                             style="font-size: 0.85rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); height: 26px;">
                                            <span class="bg-primary text-white d-flex flex-column align-items-center justify-content-center rounded-start text-center px-1"
                                                  style="font-size: 8px; margin-left: -8px; padding-top: 2px; padding-bottom: 2px; min-width: 16px; margin-right: 6px; height: 100%;">
                                                <span class="text-warning" style="font-size: 6px; line-height: 1;">★</span>
                                                <span class="fw-bold" style="line-height: 1; margin-top: 1px;">P</span>
                                            </span>
                                            <span style="letter-spacing: 0.5px;">{{ $vehicle->plate }}</span>
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
                                <td class="text-end text-nowrap align-middle style-actions-cell" style="width: 160px;">
                                    <div class="btn-group shadow-premium-vehicle" role="group"
                                        aria-label="Ações de Viatura">

                                        <a href="{{ route('vehicles.show', $vehicle->id) }}"
                                            class="btn btn-vehicle-group" data-bs-toggle="tooltip"
                                            title="Ver Detalhes da Viatura">
                                            <i class="bi bi-eye text-secondary"></i>
                                        </a>

                                        <a href="{{ route('vehicles.edit', $vehicle->id) }}"
                                            class="btn btn-vehicle-group" data-bs-toggle="tooltip"
                                            title="Editar Viatura">
                                            <i class="bi bi-pencil text-dark"></i>
                                        </a>

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

    <style>
        .clickable-row {
            cursor: pointer;
        }

        .thumbnail-wrapper {
            width: 65px;
            height: 45px;
            overflow: hidden;
            background-color: #212529;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .img-thumbnail-custom {
            width: 100%;
            height: 100%;
            object-fit: cover;
            image-rendering: high-quality;
            transition: transform 0.2s ease-in-out;
        }

        tr:hover .img-thumbnail-custom {
            transform: scale(1.1);
        }

        .shadow-premium-vehicle {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.09), 0 1px 4px rgba(0, 0, 0, 0.04) !important;
            border-radius: 20px;
            display: inline-flex;
        }

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

        .btn-vehicle-group i {
            font-size: 1.05rem;
            line-height: 1;
        }

        .btn-vehicle-group:not(:first-child) {
            border-left: 1px solid #e2e8f0 !important;
        }

        .btn-vehicle-group:hover {
            background-color: #f8f9fa !important;
        }

        .btn-vehicle-danger:hover {
            background-color: rgba(220, 53, 69, 0.08) !important;
        }

        .btn-vehicle-danger:hover i {
            color: #bb2d3b !important;
        }

        .btn-group>.btn-vehicle-group:first-child {
            border-top-left-radius: 20px !important;
            border-bottom-left-radius: 20px !important;
        }

        .btn-group>form .btn-vehicle-group,
        .btn-group>form span .btn-vehicle-group {
            border-radius: 0 !important;
        }

        .btn-group>form:last-child .btn-vehicle-group,
        .btn-group>form:last-child span .btn-vehicle-group {
            border-top-right-radius: 20px !important;
            border-bottom-right-radius: 20px !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        .transition-icon {
            display: inline-block;
            transition: transform 0.2s ease-in-out;
        }

        [aria-expanded="true"] .transition-icon {
            transform: rotate(90deg);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const collapseEl = document.getElementById('pesquisaAvancadaAdmin');
            const btnText = document.querySelector('#btnPesquisaAvancada .btn-text');

            if (collapseEl && btnText) {
                collapseEl.addEventListener('show.bs.collapse', function() {
                    btnText.textContent = 'Ocultar';
                });

                collapseEl.addEventListener('hide.bs.collapse', function() {
                    btnText.textContent = 'Pesquisa Avançada';
                });
            }

            const rows = document.querySelectorAll('.clickable-row');
            rows.forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('.style-actions-cell') || e.target.closest('button') || e
                        .target.closest('a') || e.target.closest('form')) {
                        return;
                    }

                    const url = this.getAttribute('data-href');
                    if (url) {
                        window.location.href = url;
                    }
                });
            });
        });
    </script>
@endsection
