@foreach ($vehicles as $vehicle)
    @php
        // Normalizar o texto da BD para garantir a correspondência
        $marcaSlug = strtolower(trim($vehicle->make));

        // Se a marca existir no mapa, usa a classe dela, caso contrário usa um genérico
        $iconeClasse = $brandIcons[$marcaSlug] ?? 'fa-solid fa-car-side';
    @endphp

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden vehicle-card">
        <a href="{{ route('vehicles.show', $vehicle->id) }}"
            class="card border-0 rounded-3 overflow-hidden vehicle-card text-decoration-none text-dark mb-3">
            <div class="row g-0">
                {{-- Imagem de Stock --}}
                <div class="col-12 col-md-4 position-relative" style="min-height: 250px;">
                    @php
                        $marcaSlug = strtolower(trim($vehicle->make));

                        $caminhoFotoMarca = "images/{$marcaSlug}.jpg";

                        if (!file_exists(public_path($caminhoFotoMarca))) {
                            $caminhoFotoMarca = 'images/default_car.jpg';
                        }
                    @endphp

                    <img src="{{ $vehicle->photo ? asset('storage/' . $vehicle->photo) : asset($caminhoFotoMarca) }}"
                        alt="Viatura {{ $vehicle->make }}" class="w-100 h-100 position-absolute top-0 start-0"
                        style="object-fit: cover;">
                </div>

                {{-- Detalhes --}}
                <div class="col-12 col-md-8 d-flex flex-column justify-content-between p-4">
                    <div>
                        {{-- Cabeçalho do Cartão Optimizado com Ícones por Classe --}}
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h2 class="h4 fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <span>{{ $vehicle->make }} <span class="fw-normal text-muted"
                                        style="font-size: 1.1rem;">{{ $vehicle->model }}</span></span>
                            </h2>
                            <span class="h4 fw-bold text-primary mb-0">{{ number_format($vehicle->price, 2, ',', '.') }}
                                €</span>
                        </div>

                        <div class="h4 text-muted fw-normal mb-3">
                            {{ $vehicle->year }} &bull; {{ number_format($vehicle->mileage, 0, ',', '.') }} km
                        </div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span
                                class="badge bg-light text-dark border px-2 py-2 fw-medium">{{ $vehicle->fuel }}</span>
                        </div>
                    </div>

                    {{-- Rodapé --}}
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                        <div class="d-inline-flex align-items-center border border-dark rounded bg-white text-dark font-monospace fw-bold px-2 py-0"
                            style="font-size: 0.85rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <span
                                class="bg-primary text-white d-flex flex-column align-items-center justify-content-center rounded-start text-center px-1"
                                style="font-size: 8px; margin-left: -8px; padding-top: 2px; padding-bottom: 2px; min-width: 16px; margin-right: 6px;">
                                <span class="text-warning" style="font-size: 6px; line-height: 1;">★</span>
                                <span class="fw-bold" style="line-height: 1; margin-top: 1px;">P</span>
                            </span>
                            <span>{{ $vehicle->plate }}</span>
                        </div>
                        <span class="badge bg-success-subtle text-success px-3 py-2">Disponível</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
@endforeach
