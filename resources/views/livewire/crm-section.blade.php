<div>
    <div class="row g-4">
        {{-- 1. FORMULÁRIO DE REGISTO CRM --}}
        <div class="col-xl-5 col-lg-6">
            <div class="card shadow-premium border border-light-subtle rounded-3 overflow-hidden">
                <div class="card-header bg-light-subtle p-3 border-bottom border-light-subtle">
                    <h5 class="mb-0 h6 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-chat-left-quote text-secondary fs-5"></i> Nova Acção Comercial
                    </h5>
                </div>
                <div class="card-body bg-white p-4">
                    <form wire:submit.prevent="store" class="m-0">
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label small text-muted fw-bold text-uppercase tracking-wide mb-0"
                                        style="font-size: 0.72rem;">Data / Hora</label>
                                    <button type="button" wire:click="setNow"
                                        class="btn p-0 text-primary fw-semibold border-0 bg-transparent"
                                        style="font-size: 0.75rem;">
                                        <i class="bi bi-clock-history"></i> Agora
                                    </button>
                                </div>
                                <input type="datetime-local" wire:model="interaction_date"
                                    class="form-control shadow-sm border-light-subtle"
                                    style="height: 40px; border-radius: 6px; font-size: 0.9rem;" required>
                            </div>

                            <div class="col-sm-6">
                                <label
                                    class="form-label small text-muted fw-bold text-uppercase tracking-wide d-block mb-1"
                                    style="font-size: 0.72rem; margin-top: 3px;">Canal de Contacto</label>
                                <select wire:model="type" class="form-select shadow-sm border-light-subtle"
                                    style="height: 40px; border-radius: 6px; font-size: 0.9rem;">
                                    <option value="phone">📞 Chamada Telefónica</option>
                                    <option value="email">✉️ Correio Eletrónico</option>
                                    <option value="visit">🤝 Reunião Presencial</option>
                                    <option value="site">💻 Plataforma Digital</option>
                                </select>
                            </div>
                        </div>

                        {{-- TomSelect necessita de wire:ignore para não quebrar o Livewire --}}
                        <div class="mb-3" wire:ignore>
                            <label for="select-vehicle"
                                class="form-label small text-muted fw-bold text-uppercase tracking-wide"
                                style="font-size: 0.72rem;">Viatura sob Interesse</label>
                            <select id="select-vehicle" autocomplete="off">
                                <option value="">Pesquisar marca, modelo ou matrícula...</option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">
                                        {{ $vehicle->brand ?? $vehicle->make }} {{ $vehicle->model }}
                                        [{{ $vehicle->plate }}]
                                        @if ($vehicle->status === 'sold' || ($vehicle->is_sold ?? false))
                                            (Vendido)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="comment"
                                class="form-label small text-muted fw-bold text-uppercase tracking-wide"
                                style="font-size: 0.72rem;">Resumo da Interação</label>
                            <textarea wire:model="comment" id="comment"
                                class="form-control shadow-sm border-light-subtle @error('comment') is-invalid @enderror" rows="3"
                                placeholder="Notas detalhadas do contacto..." style="border-radius: 6px; resize: vertical; font-size: 0.9rem;"
                                required></textarea>
                            @error('comment')
                                <div class="invalid-feedback small fw-semibold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-muted fw-bold text-uppercase tracking-wide"
                                style="font-size: 0.72rem;">Ficheiro ou Documento de Suporte</label>
                            <div class="input-group shadow-sm">
                                <label class="input-group-text btn btn-light border border-light-subtle mb-0 px-3"
                                    for="livewire-attachment"
                                    style="border-top-left-radius: 6px; border-bottom-left-radius: 6px; cursor: pointer;">
                                    <i class="bi bi-paperclip text-secondary"></i>
                                </label>
                                <input type="text" class="form-control bg-white border-light-subtle border-start-0"
                                    placeholder="{{ $attachment ? $attachment->getClientOriginalName() : 'Nenhum ficheiro anexado' }}"
                                    readonly
                                    style="border-top-right-radius: 6px; border-bottom-right-radius: 6px; font-size: 0.85rem;">
                            </div>
                            <input type="file" wire:model="attachment" id="livewire-attachment" class="d-none">
                        </div>

                        <div class="d-grid">
                            <button type="submit"
                                class="btn btn-secondary border-0 rounded-2 py-2 fw-semibold shadow-sm text-white"
                                style="background-color: #495057;">
                                <i class="bi bi-file-earmark-plus me-1"></i> Adicionar ao Histórico
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 2. LINHA DE TEMPO / HISTÓRICO CRM --}}
        <div class="col-xl-7 col-lg-6">
            <div class="card shadow-premium border border-light-subtle rounded-3 overflow-hidden h-100">
                <div class="card-header bg-light-subtle p-3 border-bottom border-light-subtle">
                    <h5 class="mb-0 h6 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-journal-text text-secondary fs-5"></i> Linha de Tempo CRM
                    </h5>
                </div>
                <div class="card-body bg-light-subtle p-3">
                    <ul class="list-group list-group-flush border-0 bg-transparent ps-0 w-100">
                        @forelse($interactions as $interaction)
                            <li class="list-group-item bg-white border border-light-subtle rounded-3 shadow-sm mb-3 p-3 w-100 position-relative"
                                wire:key="interaction-{{ $interaction->id }}">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        @switch($interaction->type)
                                            @case('phone')
                                                <span class="badge badge-pastel-blue">Telefone</span>
                                            @break

                                            @case('email')
                                                <span class="badge badge-pastel-yellow">Email</span>
                                            @break

                                            @case('visit')
                                                <span class="badge badge-pastel-green">Presencial</span>
                                            @break

                                            @case('site')
                                                <span class="badge badge-pastel-orange">Digital</span>
                                            @break

                                            @default
                                                <span
                                                    class="badge bg-light text-dark border">{{ ucfirst($interaction->type) }}</span>
                                        @endswitch

                                        <span class="text-muted fw-semibold" style="font-size: 0.75rem;">
                                            por
                                            @if ($interaction->user)
                                                <span
                                                    class="text-secondary fw-semibold ms-1">{{ ucwords(strtolower($interaction->user->name)) }}</span>
                                            @else
                                                <span class="text-muted fw-normal italic ms-1">Sistema</span>
                                            @endif
                                        </span>
                                    </div>
                                    <span class="text-muted fw-semibold" style="font-size: 0.8rem;">
                                        {{ \Carbon\Carbon::parse($interaction->date)->format('d/m/Y \à\s H:i') }}
                                    </span>
                                </div>

                                {{-- blade-formatter-disable --}}
                                <div class="text-dark bg-light p-3 rounded-2 border-start border-3 border-secondary text-start mb-2" style="line-height: 1.5; font-size: 0.9rem; white-space: pre-wrap;">{{ $interaction->comment ?? $interaction->notes }}</div>
                                {{-- blade-formatter-enable --}}

                                @if ($interaction->vehicle)
                                    <div class="bg-light-subtle p-2 rounded border border-light d-flex align-items-center gap-2 mb-2"
                                        style="font-size: 0.82rem;">
                                        <i class="bi bi-car-front text-muted"></i>
                                        <span class="text-muted">Relacionado com:</span>
                                        <a href="{{ route('vehicles.show', $interaction->vehicle->id) }}"
                                            class="fw-bold text-decoration-none text-primary">
                                            {{ $interaction->vehicle->brand ?? $interaction->vehicle->make }}
                                            {{ $interaction->vehicle->model }}
                                        </a>
                                    </div>
                                @endif

                                @if ($interaction->attachment)
                                    <div class="border-top border-light pt-2 mt-2">
                                        <small class="text-uppercase tracking-wider text-muted fw-bold d-block mb-1"
                                            style="font-size: 0.68rem;">
                                            <i class="bi bi-paperclip"></i> Anexo
                                        </small>
                                        <div class="d-flex align-items-center gap-2 py-1">
                                            @php
                                                $extension = strtolower(
                                                    pathinfo($interaction->attachment, PATHINFO_EXTENSION),
                                                );

                                                switch ($extension) {
                                                    case 'pdf':
                                                        $fileLabel = 'Documento PDF';
                                                        $icon = 'bi-file-earmark-pdf text-danger';
                                                        break;
                                                    case 'jpg':
                                                    case 'jpeg':
                                                    case 'png':
                                                    case 'webp':
                                                    case 'gif':
                                                        $fileLabel = 'Imagem';
                                                        $icon = 'bi-file-earmark-image text-success';
                                                        break;
                                                    case 'doc':
                                                    case 'docx':
                                                        $fileLabel = 'Documento Word';
                                                        $icon = 'bi-file-earmark-word text-primary';
                                                        break;
                                                    case 'xls':
                                                    case 'xlsx':
                                                    case 'csv':
                                                        $fileLabel = 'Folha de Cálculo';
                                                        $icon = 'bi-file-earmark-excel text-success';
                                                        break;
                                                    case 'zip':
                                                    case 'rar':
                                                        $fileLabel = 'Arquivo Compactado';
                                                        $icon = 'bi-file-zip text-warning';
                                                        break;
                                                    default:
                                                        $fileLabel = 'Ficheiro Anexo';
                                                        $icon = 'bi-file-earmark text-secondary';
                                                }
                                            @endphp

                                            <i class="bi {{ $icon }}" style="font-size: 0.95rem;"></i>
                                            <a href="{{ asset('storage/' . $interaction->attachment) }}"
                                                target="_blank"
                                                class="text-primary text-decoration-none fw-medium small">
                                                {{ $fileLabel }} <span class="text-muted fw-normal"
                                                    style="font-size: 0.75rem;">(.{{ $extension }})</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @can('admin-only')
                                    <div class="d-flex justify-content-end mt-2">
                                        <button type="button" wire:click="destroyInteraction({{ $interaction->id }})"
                                            wire:confirm="Deseja remover esta interação do histórico?"
                                            class="btn btn-sm btn-link text-danger p-0 text-decoration-none d-inline-flex align-items-center gap-1"
                                            style="font-size: 0.75rem; font-weight: 500;">
                                            <i class="bi bi-trash3 small"></i> Eliminar
                                        </button>
                                    </div>
                                @endcan
                            </li>
                            @empty
                                <li
                                    class="list-group-item text-center text-muted py-5 bg-white rounded-3 border border-light-subtle ps-0">
                                    <i class="bi bi-chat-square-dots fs-2 text-muted opacity-50 d-block mb-2"></i>
                                    <span class="small fw-medium">Histórico limpo. Nenhuma atividade registada para este
                                        cliente.</span>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sincronização do TomSelect com Livewire --}}
        <script>
            document.addEventListener('livewire:init', () => {
                const el = document.getElementById('select-vehicle');
                if (el) {
                    const ts = new TomSelect(el, {
                        create: false,
                        maxItems: 1,
                        allowEmptyOption: true,
                        placeholder: "Pesquisar marca, modelo ou matrícula...",
                        sortField: {
                            field: "text",
                            direction: "asc"
                        }
                    });
                    ts.on('change', (value) => {
                        @this.set('vehicle_id', value);
                    });
                }
            });
        </script>
    </div>
