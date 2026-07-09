@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        /* Estilos Globais e de Animação do Banner */
        .top-banner-notification {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 56px;
            z-index: 9999;
            animation: slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .top-banner-notification.hide-banner {
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes slideDown { from { transform: translateY(-100%); } to { transform: translateY(0); } }
        @keyframes slideUp { from { transform: translateY(0); } to { transform: translateY(-100%); } }

        .shadow-premium { box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05) !important; }
        .action-buttons-wrapper { box-shadow: 0 4px 10px rgba(0, 0, 0, 0.09), 0 1px 4px rgba(0, 0, 0, 0.04) !important; border-radius: 20px; overflow: hidden; display: inline-flex; border: 1px solid #e2e8f0; }
        .btn-action-group { width: 47px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border: none !important; transition: all 0.15s ease-in-out; margin: 0; border-radius: 0 !important; }
        .btn-action-group i { font-size: 1.05rem; line-height: 1; }
        .btn-action-group:not(:first-child), .btn-group>form:not(:first-child) .btn-action-group { border-left: 1px solid #e2e8f0 !important; }
        .btn-group-default { background-color: #ffffff !important; }
        .btn-group-default:hover { background-color: #f8f9fa !important; }
        .btn-group-danger:hover { background-color: rgba(220, 53, 69, 0.08) !important; }

        /* Badges e Estilos do Histórico */
        .badge-pastel-blue { background-color: #f0f4f8 !important; color: #1e5680 !important; border: 1px solid #d3e2f0 !important; }
        .badge-pastel-yellow { background-color: #faf7ed !important; color: #735a13 !important; border: 1px solid #f2e7c4 !important; }
        .badge-pastel-green { background-color: #f1f6f1 !important; color: #204d20 !important; border: 1px solid #d4ebd4 !important; }
        .badge-pastel-orange { background-color: #fcf4f1 !important; color: #823210 !important; border: 1px solid #f5dad0 !important; }
        .badge { padding: 0.45em 0.7em !important; font-weight: 600 !important; font-size: 0.72rem !important; border-radius: 4px !important; }
        .table> :not(caption)>*>* { border-bottom-color: #f1f3f5; }
    </style>
@endpush

@section('content')
    {{-- Contentor dinâmico onde o JavaScript vai injetar o banner verde vindo do Livewire --}}
    <div id="livewire-banner-container"></div>

    <div class="container pt-4">
        {{-- Cabeçalho Superior da Página --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h1 mb-0 text-dark fw-bold">{{ $client->name }}</h2>
            </div>
            <a href="{{ route('clients.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left me-1"></i> Voltar à Listagem
            </a>
        </div>

        {{-- Bloco Superior: Perfil do Cliente & Vendas Associadas --}}
        <div class="row g-4 mb-4">
            {{-- Coluna Principal: Dados Gerais do Cliente --}}
            <div class="col-lg-8">
                <div class="card shadow-premium border border-light-subtle rounded-3 h-100 overflow-hidden">
                    <div class="card-header bg-light-subtle d-flex justify-content-between align-items-center px-4 py-3 border-bottom border-light-subtle">
                        <div class="d-flex align-items-center gap-2 text-truncate" style="max-width: 65%;">
                            <i class="bi bi-person-card-heading text-secondary fs-5"></i>
                            <h4 class="mb-0 fw-bold h5 text-dark text-truncate">Dados do cliente</h4>
                        </div>

                        <div class="action-buttons-wrapper">
                            <div class="btn-group" role="group" aria-label="Ações do Cliente">
                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-action-group btn-group-default" data-bs-toggle="tooltip" title="Editar Cliente">
                                    <i class="bi bi-pencil text-dark"></i>
                                </a>
                                @can('admin-only')
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-group btn-group-danger btn-group-default" onclick="return confirm('Tem a certeza que deseja eliminar definitivamente este cliente?')" data-bs-toggle="tooltip" title="Eliminar Cliente">
                                            <i class="bi bi-trash3 text-danger"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <div class="card-body bg-white p-4">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <small class="text-uppercase tracking-wider text-muted fw-bold d-block mb-1" style="font-size: 0.72rem;">ID de Registo</small>
                                <span class="text-dark fw-medium">#{{ $client->id }}</span>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-uppercase tracking-wider text-muted fw-bold d-block mb-1" style="font-size: 0.72rem;">NIF / Identificação Fiscal</small>
                                <span class="text-dark fw-medium">{{ $client->taxId ?? '---' }}</span>
                            </div>
                            <div class="col-sm-6 border-top border-light pt-3">
                                <small class="text-uppercase tracking-wider text-muted fw-bold d-block mb-1" style="font-size: 0.72rem;">Endereço Eletrónico</small>
                                <a href="mailto:{{ $client->email }}" class="text-primary text-decoration-none fw-medium">{{ $client->email }}</a>
                            </div>
                            <div class="col-sm-6 border-top border-light pt-3">
                                <small class="text-uppercase tracking-wider text-muted fw-bold d-block mb-1" style="font-size: 0.72rem;">Telemóvel / Telefone</small>
                                <a href="tel:{{ $client->phone }}" class="text-dark text-decoration-none fw-medium">{{ $client->phone }}</a>
                            </div>
                            <div class="col-12 border-top border-light pt-3">
                                <small class="text-uppercase tracking-wider text-muted fw-bold d-block mb-1" style="font-size: 0.72rem;">Morada Completa</small>
                                <span class="text-secondary fw-medium">{{ $client->address ?? 'Nenhuma morada associada à ficha deste cliente.' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light-subtle border-top border-light-subtle px-4 py-2 text-end">
                        <small class="text-muted" style="font-size: 0.78rem;">Ficha criada a: {{ $client->created_at->format('d/m/Y \à\s H:i') }}</small>
                    </div>
                </div>
            </div>

            {{-- Coluna Direita: Histórico de Compras --}}
            <div class="col-lg-4">
                <div class="card shadow-premium border border-light-subtle rounded-3 h-100 overflow-hidden d-flex flex-column">
                    <div class="card-header bg-light-subtle px-3 py-3 border-bottom border-light-subtle fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 0.85rem; letter-spacing: 0.3px;">
                        <i class="bi bi-cart-check text-secondary fs-5"></i> HISTÓRICO DE COMPRAS
                    </div>
                    <div class="card-body p-0 bg-white overflow-auto flex-grow-1" style="max-height: 242px;">
                        <table class="table table-hover mb-0 align-middle">
                            <tbody>
                                @forelse ($client->sales as $sale)
                                    <tr>
                                        <td class="p-3 border-0 border-bottom border-light position-relative">
                                            <a href="{{ route('sales.show', $sale->id) }}" class="stretched-link" aria-label="Ver detalhe da venda"></a>
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span class="fw-bold text-dark text-truncate d-block" style="max-width: 70%; font-size: 0.9rem;">
                                                    {{ $sale->vehicle->make ?? 'N/D' }} {{ $sale->vehicle->model ?? 'N/D' }}
                                                </span>
                                                <span class="text-muted small fw-medium">{{ $sale->created_at->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="d-inline-flex align-items-center border border-dark rounded bg-white text-dark fw-bold px-2 py-0" style="font-size: 0.72rem; height: 18px; box-shadow: 0 1px 2px rgba(0,0,0,0.02); position: relative; z-index: 2;">
                                                <span class="bg-primary text-white d-flex flex-column align-items-center justify-content-center rounded-start px-1" style="font-size: 5px; margin-left: -8px; margin-right: 4px; height: 100%; min-width: 9px;"><span>P</span></span>
                                                <span style="letter-spacing: 0.5px;">{{ $sale->vehicle->plate }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="p-4 text-center text-muted border-0 pt-5">
                                            <i class="bi bi-tag text-muted opacity-50 fs-3 d-block mb-1"></i>
                                            <span class="small fw-medium">Nenhum registo de aquisição.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHAMADA DO COMPONENTE COMPARTIMENTADO DO CRM --}}
        @livewire(App\Livewire\CrmSection::class, ['client' => $client])
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Escuta os eventos emitidos pelo Livewire para criar o Banner de Sucesso dinâmico no topo
            window.addEventListener('crm-success', event => {
                const container = document.getElementById('livewire-banner-container');
                const banner = document.createElement('div');
                banner.className = 'top-banner-notification bg-success text-white shadow-lg d-flex align-items-center justify-content-center px-4';
                banner.innerHTML = `
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <span class="fw-semibold">${event.detail.message}</span>
                    </div>
                `;
                container.appendChild(banner);

                // Desce o banner, aguarda 5 segundos e remove com suavidade
                setTimeout(() => {
                    banner.classList.add('hide-banner');
                    setTimeout(() => banner.remove(), 400);
                }, 5000);
            });
        });
    </script>
@endpush
