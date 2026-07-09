@extends('layouts.app')

@section('content')
<div class="container pt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Editar Registo de Viatura</h2>
        <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Cancelar e Voltar
        </a>
    </div>

    {{-- Alerts de Sucesso ou Erro --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <hr>

    @php
        // --- MESMA LÓGICA DE DETEÇÃO DE IMAGEM DA VIEW SHOW ---
        $marcaSlug = strtolower(trim($vehicle->make));
        $caminhoFotoMarca = "images/{$marcaSlug}.jpg";
        if (!file_exists(public_path($caminhoFotoMarca))) {
            $caminhoFotoMarca = "images/default_car.jpg";
        }

        $fotosReais = [];
        $diretorioVeiculo = "vehicles/{$vehicle->id}";

        if (\Storage::disk('public')->exists($diretorioVeiculo)) {
            $ficheiros = \Storage::disk('public')->files($diretorioVeiculo);
            if (!empty($ficheiros)) {
                sort($ficheiros);
                foreach ($ficheiros as $ficheiro) {
                    $fotosReais[] = asset('storage/' . $ficheiro);
                }
            }
        }

        if (empty($fotosReais) && $vehicle->photo && \Storage::disk('public')->exists($vehicle->photo)) {
            $fotosReais[] = asset('storage/' . $vehicle->photo);
        }

        $temFotoReal = !empty($fotosReais);
        $fotoPrincipal = $temFotoReal ? $fotosReais[0] : asset($caminhoFotoMarca);
    @endphp

    <div class="card shadow-sm border-0 overflow-hidden rounded-3">
        <div class="row g-0">

            {{-- Coluna da Esquerda (Identica à Show): Visualização e Gestão Rápida de Imagem --}}
            <div class="col-md-5 bg-dark position-relative d-flex flex-column align-items-center justify-content-center p-3" style="min-height: 350px;">
                <img src="{{ $fotoPrincipal }}"
                     id="edit-photo-preview"
                     alt="Foto da Viatura"
                     class="w-100 h-100 position-absolute top-0 start-0"
                     style="object-fit: cover; image-rendering: high-quality; opacity: 0.4;">

                {{-- Controlos Flutuantes da Imagem por cima do fundo escuro --}}
                <div class="position-relative z-1 text-center w-100 px-3 text-white">
                    <i class="bi bi-camera display-4 mb-2 text-white-50"></i>
                    <h5 class="fw-bold mb-1">Fotografia de Stock</h5>
                    <p class="small text-white-50 mb-3">
                        {{ $temFotoReal ? 'Esta viatura possui ' . count($fotosReais) . ' foto(s) na galeria.' : 'A utilizar imagem padrão da marca.' }}
                    </p>

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-sm btn-light shadow-sm" onclick="document.getElementById('quick-photo-input').click();">
                            <i class="bi bi-upload"></i> {{ $temFotoReal ? 'Substituir / Adicionar' : 'Carregar Foto' }}
                        </button>

                        @if($temFotoReal)
                            <button type="button" class="btn btn-sm btn-outline-danger border-white text-white" onclick="if(confirm('Tem a certeza que deseja limpar a galeria desta viatura?')) { document.getElementById('delete-all-photos-form').submit(); }">
                                <i class="bi bi-trash3"></i> Limpar
                            </button>
                        @endif
                    </div>

                    <div id="compression-loader" class="text-warning d-none small mt-2 fw-medium">
                        <i class="bi bi-hourglass-split animate-spin"></i> Otimizar imagem para upload...
                    </div>
                </div>
            </div>

            {{-- Coluna da Direita: Formulário de Dados Reconstruído sobre o Grid da Show --}}
            <div class="col-md-7 d-flex flex-column justify-content-between bg-white">
                <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div>
                        {{-- Cabeçalho do Cartão --}}
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center p-3">
                            <h4 class="mb-0 fw-bold">Modo de Edição</h4>
                            <span class="badge bg-warning text-dark px-3 py-2 fs-6 fw-bold">
                                ID #{{ $vehicle->id }}
                            </span>
                        </div>

                        {{-- Corpo dos Inputs Harmonizado --}}
                        <div class="card-body">

                            {{-- Linha 1: Marca e Modelo --}}
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Marca</label>
                                    <input type="text" name="make" class="form-control @error('make') is-invalid @enderror" value="{{ old('make', $vehicle->make) }}">
                                    @error('make') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Modelo</label>
                                    <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model', $vehicle->model) }}">
                                    @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Bloco Técnico Principal (Mimetiza o bloco cinzento da show) --}}
                            <div class="bg-light p-3 rounded-3 mb-3 border">
                                <div class="row g-2 text-start">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold text-secondary font-monospace mb-1">ANO</label>
                                        <input type="number" name="year" class="form-control form-control-sm @error('year') is-invalid @enderror" value="{{ old('year', $vehicle->year) }}">
                                        @error('year') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold text-secondary font-monospace mb-1">QUILÓMETROS (km)</label>
                                        <input type="number" name="mileage" class="form-control form-control-sm @error('mileage') is-invalid @enderror" value="{{ old('mileage', $vehicle->mileage) }}">
                                        @error('mileage') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold text-secondary font-monospace mb-1">PREÇO (€)</label>
                                        <input type="number" step="0.01" name="price" class="form-control form-control-sm fw-bold text-primary @error('price') is-invalid @enderror" value="{{ old('price', $vehicle->price) }}">
                                        @error('price') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Detalhes Adicionais: Matrícula e Estado --}}
                            <div class="row g-2 mb-2">
                                <div class="col-sm-6">
                                    <label class="form-label small fw-bold text-muted mb-1">Matrícula</label>
                                    <input type="text" name="plate" class="form-control font-monospace fw-bold @error('plate') is-invalid @enderror" value="{{ old('plate', $vehicle->plate) }}" placeholder="AA-00-AA">
                                    @error('plate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label small fw-bold text-muted mb-1">Estado de Venda</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="available" {{ old('status', $vehicle->status) == 'available' ? 'selected' : '' }}>Disponível</option>
                                        <option value="sold" {{ old('status', $vehicle->status) == 'sold' ? 'selected' : '' }}>Vendido</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Rodapé de Ações Unificado --}}
                    <div class="card-footer bg-light border-top p-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Última edição: {{ $vehicle->updated_at->format('d/m/Y') }}</span>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success px-4 fw-medium">
                                <i class="bi bi-check-circle"></i> Gravar Alterações
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- Formulários Invisíveis Auxiliares (Para Manutenção de Fotos sem recarregar a página de dados) --}}
<form id="quick-photo-form" action="{{ route('vehicles.upload_photo', $vehicle->id) }}" method="POST" enctype="multipart/form-data" class="d-none">
    @csrf
    <input type="file" name="photo" id="quick-photo-input" accept="image/*" onchange="processAndSubmitEditPhoto(this)">
</form>

@if($temFotoReal)
<form id="delete-all-photos-form" action="{{ route('vehicles.delete_photo', $vehicle->id) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
    {{-- Passa a primeira url para o teu método tradicional saber o que expurgar --}}
    <input type="hidden" name="photo_url" value="{{ $fotosReais[0] }}">
</form>
@endif

<script>
function processAndSubmitEditPhoto(input) {
    if (!input.files || !input.files[0]) return;

    const file = input.files[0];
    const reader = new FileReader();
    const loader = document.getElementById('compression-loader');

    loader.classList.remove('d-none');
    document.body.style.cursor = 'wait';

    reader.onload = function (e) {
        // Atualiza dinamicamente o fundo opaco para dar feedback visual instantâneo
        document.getElementById('edit-photo-preview').src = e.target.result;

        const img = new Image();
        img.src = e.target.result;
        img.onload = function () {
            const canvas = document.createElement('canvas');
            const maxW = 1200;
            let width = img.width;
            let height = img.height;

            if (width > maxW) {
                height = Math.round((height * maxW) / width);
                width = maxW;
            }

            canvas.width = width;
            canvas.height = height;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            canvas.toBlob(function (blob) {
                const resizedFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".jpg", {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(resizedFile);
                input.files = dataTransfer.files;

                // Submete para o upload automático
                document.getElementById('quick-photo-form').submit();
            }, 'image/jpeg', 0.75);
        };
    };
    reader.readAsDataURL(file);
}
</script>
@endsection
