@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

@section('content')
    <div class="container">
        <h2>Detalhes do Cliente</h2>
        <hr>
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4>{{ $client->name }}</h4>
            </div>
            <div class="card-body">
                <p><strong>ID Interno:</strong> {{ $client->id }}</p>
                <p><strong>Email:</strong> {{ $client->email }}</p>
                <p><strong>Telefone:</strong> {{ $client->phone }}</p>
                <p><strong>NIF:</strong> {{ $client->taxId }}</p>
                <p><strong>Morada:</strong> {{ $client->address }}</p>
                <p><strong>Data de Registo:</strong> {{ $client->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning text-white">Editar</a>
                @can('admin-only')
                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Tem a certeza que deseja eliminar definitivamente este cliente?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Eliminar Cliente
                        </button>
                    </form>
                @endcan
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Voltar à Listagem</a>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">Vendas Realizadas</div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-striped mb-0">
                            @forelse ($client->sales as $sale)
                                <tr>
                                    <td>
                                        <small class="text-muted">{{ $sale->created_at->format('d/m/Y') }}</small><br>
                                        <strong>{{ $sale->vehicle->brand ?? $sale->vehicle->make }}
                                            {{ $sale->vehicle->model }}</strong><br>
                                        <span class="badge bg-secondary">{{ $sale->vehicle->plate }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="p-3 text-muted shadow-none">Sem vendas registadas para este cliente.</td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-header">Registar Nova Interação</div>
                    <div class="card-body">
                        <form action="{{ route('crm.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="client_id" value="{{ $client->id }}">

                            {{-- Linha Superior: Data, Tipo e a Pesquisa de Viatura --}}
                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label class="form-label small text-muted font-weight-medium">Data</label>
                                    <input type="date" name="date" class="form-control shadow-sm"
                                        style="height: 42px;" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label small text-muted font-weight-medium">Tipo de Contacto</label>
                                    <select name="type" class="form-select shadow-sm" style="height: 42px;">
                                        <option value="phone">Telefone</option>
                                        <option value="email">Email</option>
                                        <option value="visit">Visita ao Stand</option>
                                        <option value="site">Formulário (site)</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="select-vehicle"
                                        class="form-label small text-muted font-weight-medium">Viatura Associada</label>
                                    <select name="vehicle_id" id="select-vehicle" autocomplete="off">
                                        <option value="">Digite para pesquisar por marca, modelo ou matrícula...</option>
                                        @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}">
                                                {{ $vehicle->brand ?? $vehicle->make }} {{ $vehicle->model }} - {{ $vehicle->plate }}
                                                @if ($vehicle->status === 'sold' || ($vehicle->is_sold ?? false))
                                                    (Vendido)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Linha Inferior: Caixa de Comentários Ampla e Alta --}}
                            <div class="mb-3">
                                <label for="comment" class="form-label small text-muted font-weight-medium">Comentários / Notas da Interação</label>
                                <textarea name="comment" id="comment" class="form-control shadow-sm" rows="3"
                                    placeholder="Escreva aqui o resumo detalhado da conversa, objeções do cliente ou próximos passos..."
                                    style="border-radius: 8px; resize: vertical;"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success px-4">Guardar Interação</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Histórico de Contactos</div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($client->interactions as $interaction)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                    <div>
                                        @switch($interaction->type)
                                            @case('phone')
                                                <span class="badge badge-pastel-blue me-2">tel</span>
                                            @break
                                            @case('email')
                                                <span class="badge badge-pastel-yellow me-2">email</span>
                                            @break
                                            @case('visit')
                                                <span class="badge badge-pastel-green me-2">visita</span>
                                            @break
                                            @case('site')
                                                <span class="badge badge-pastel-orange me-2">site</span>
                                            @break
                                            @default
                                                <span class="badge bg-light text-dark border me-2">{{ ucfirst($interaction->type) }}</span>
                                        @endswitch

                                        <span class="text-dark">{{ $interaction->comment }}</span>

                                        @if ($interaction->vehicle)
                                            <small class="text-muted d-block mt-1">
                                                <i class="bi bi-car-front me-1"></i> Viatura:
                                                <strong>{{ $interaction->vehicle->brand ?? $interaction->vehicle->make }} {{ $interaction->vehicle->model }}</strong>
                                                (Matrícula: <a href="{{ route('vehicles.show', $interaction->vehicle->id) }}"
                                                    class="fw-bold text-decoration-none text-primary">
                                                    {{ $interaction->vehicle->plate }}
                                                </a>)
                                            </small>
                                        @endif
                                    </div>
                                    <small class="text-muted fw-bold">{{ \Carbon\Carbon::parse($interaction->date)->format('d/m/Y') }}</small>
                                </li>
                            @empty
                                <li class="list-group-item text-muted py-3">Sem registos de contactos anteriores no CRM para este cliente.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div> {{-- Fecho correto da div container --}}

    {{-- Scripts movidos para dentro da diretiva correta antes do fim da section --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inicialização Nativa Segura (Look Google garantido por CSS)
            new TomSelect("#select-vehicle", {
                create: false,
                maxItems: 1,
                allowEmptyOption: true,
                placeholder: "Digite a marca, modelo ou matrícula...",
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    </script>

    <style>
        /* CSS unificado para estilizar a caixa e garantir envio total dos dados */
        .ts-wrapper.single .ts-control {
            background-color: #ffffff !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 8px !important;
            padding: 0.5rem 0.75rem !important;
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            min-height: 42px !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
            cursor: text;
        }

        .ts-wrapper.single.focus .ts-control {
            border-color: #86b7fe !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }

        .ts-dropdown {
            background: #ffffff !important;
            border: none !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
            border-radius: 8px !important;
            margin-top: 5px !important;
            z-index: 1050 !important;
        }

        .ts-dropdown .active {
            background-color: #f1f3f5 !important;
            color: #212529 !important;
        }

        .ts-dropdown .option {
            padding: 0.6rem 1rem !important;
            cursor: pointer;
        }

        .ts-wrapper.single .ts-control::after {
            display: none !important;
        }

        /* Classes de Cores Pastel Suaves */
        .badge-pastel-blue { background-color: #e7f1ff !important; color: #0c63e4 !important; border: 1px solid #b6d4fe !important; }
        .badge-pastel-yellow { background-color: #fff3cd !important; color: #664d03 !important; border: 1px solid #ffe69c !important; }
        .badge-pastel-green { background-color: #e2f0d9 !important; color: #385723 !important; border: 1px solid #c5e1a5 !important; }
        .badge-pastel-orange { background-color: #ffe5d9 !important; color: #ca5212 !important; border: 1px solid #fec5bb !important; }

        .badge {
            padding: 0.45em 0.75em !important;
            font-weight: 600 !important;
            font-size: 0.8rem !important;
            border-radius: 6px !important;
        }
    </style>
@endsection
