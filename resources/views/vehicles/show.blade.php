@extends('layouts.app')

@section('content')
    <div class="container pt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Detalhes da Viatura</h2>
            <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar ao Stock
            </a>
        </div>

        {{-- Alerts de Sucesso ou Erro de Limite --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <hr>

        @php
            // --- RECONSTRUÇÃO INTELIGENTE DA GALERIA IGUAL AO INDEX ---
            // 1. Fallback da marca por defeito
            $marcaSlug = strtolower(trim($vehicle->make));
            $caminhoFotoMarca = "images/{$marcaSlug}.jpg";
            if (!file_exists(public_path($caminhoFotoMarca))) {
                $caminhoFotoMarca = 'images/default_car.jpg';
            }

            $fotosReais = [];
            $diretorioVeiculo = "vehicles/{$vehicle->id}";

            // 2. Procura ficheiros na pasta real do veículo
            if (\Storage::disk('public')->exists($diretorioVeiculo)) {
                $ficheiros = \Storage::disk('public')->files($diretorioVeiculo);
                if (!empty($ficheiros)) {
                    sort($ficheiros);
                    foreach ($ficheiros as $ficheiro) {
                        $fotosReais[] = asset('storage/' . $ficheiro);
                    }
                }
            }

            // 3. Fallback para caminho antigo na raiz se não houver diretório por ID
            if (empty($fotosReais) && $vehicle->photo && \Storage::disk('public')->exists($vehicle->photo)) {
                $fotosReais[] = asset('storage/' . $vehicle->photo);
            }

            // 4. Se encontrou fotos reais usa-as, caso contrário usa o padrão da marca
            $temFotoReal = !empty($fotosReais);
            $galeriaFinal = $temFotoReal ? $fotosReais : [asset($caminhoFotoMarca)];
        @endphp

        <div class="card shadow-sm border-0 overflow-hidden rounded-3">
            <div class="row g-0">
                {{-- Coluna da Esquerda: Visualização da Imagem --}}
                <div class="col-md-5 bg-dark position-relative d-flex align-items-center justify-content-center"
                    style="min-height: 350px;">
                    <img src="{{ $galeriaFinal[0] }}" id="lightbox-trigger" alt="Foto da Viatura"
                        class="w-100 h-100 position-absolute top-0 start-0"
                        style="object-fit: cover; image-rendering: high-quality; cursor: pointer;">

                    @if ($temFotoReal)
                        <span
                            class="position-absolute bottom-0 end-0 bg-dark text-white opacity-75 px-2 py-1 small m-2 rounded">
                            <i class="bi bi-images"></i> 1 / {{ count($galeriaFinal) }} (Clique para ver galeria)
                        </span>
                    @endif
                </div>

                {{-- Coluna da Direita: Dados e Gestão --}}
                <div class="col-md-7 d-flex flex-column justify-content-between">
                    <div>
                        {{-- Cabeçalho --}}
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center p-3">
                            <h4 class="mb-0 fw-bold">{{ $vehicle->make }} {{ $vehicle->model }}</h4>

                            <div class="d-flex align-items-center gap-2">
                                @if ($vehicle->status === 'sold')
                                    @php
                                        // Procura a venda associada a este carro
                                        $sale = \App\Models\Sale::where('vehicle_id', $vehicle->id)->first();
                                    @endphp

                                    @if ($sale)
                                        <a href="{{ route('sales.show', $sale->id) }}"
                                            class="badge bg-danger text-white text-decoration-none shadow-sm p-2">
                                            Vendido <i class="bi bi-eye-fill ms-1"></i>
                                        </a>
                                        Dad
                                    @else
                                        <span class="badge bg-danger p-2">Vendido</span>
                                    @endif
                                @else
                                    {{-- Se não está vendido, mostra a tag Disponível E o botão de Iniciar Venda ao lado --}}
                                    <span class="badge bg-success p-2">Disponível</span>

                                    <a href="{{ route('sales.create', ['vehicle_id' => $vehicle->id]) }}"
                                        class="btn btn-sm btn-outline-success btn-venda-custom fw-bold px-3">
                                        <i class="bi bi-cart-plus me-1"></i> Iniciar Venda
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Dados Técnicos Principais --}}
                        <div class="card-body">
                            <div class="row bg-light p-3 rounded-3 mb-4 text-center g-0 border">
                                <div class="col-4 border-end">
                                    <small class="text-muted d-block uppercase font-monospace">Ano</small>
                                    <span class="fs-5 fw-bold text-dark">{{ $vehicle->year }}</span>
                                </div>
                                <div class="col-4 border-end">
                                    <small class="text-muted d-block uppercase font-monospace">Quilómetros</small>
                                    <span
                                        class="fs-5 fw-bold text-dark">{{ number_format($vehicle->mileage, 0, ',', '.') }}
                                        km</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block uppercase font-monospace">Preço</small>
                                    <span
                                        class="fs-5 fw-bold text-primary">{{ number_format($vehicle->price, 2, ',', '.') }}
                                        €</span>
                                </div>
                            </div>

                            {{-- Detalhes de Registo --}}
                            <div class="row mb-4 align-items-center">
                                <div class="col-sm-6 mb-2">
                                    <span class="text-muted d-block small mb-1">Matrícula</span>
                                    {{-- Matrícula Realista --}}
                                    <div class="d-inline-flex align-items-center border border-dark rounded bg-white text-dark font-monospace fw-bold px-2 py-0"
                                        style="font-size: 0.9rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); height: 28px;">
                                        <span
                                            class="bg-primary text-white d-flex flex-column align-items-center justify-content-center rounded-start text-center px-1"
                                            style="font-size: 8px; margin-left: -8px; padding-top: 2px; padding-bottom: 2px; min-width: 16px; margin-right: 6px; height: 100%;">
                                            <span class="text-warning" style="font-size: 6px; line-height: 1;">★</span>
                                            <span class="fw-bold" style="line-height: 1; margin-top: 1px;">P</span>
                                        </span>
                                        <span style="letter-spacing: 0.5px;">{{ $vehicle->plate }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <span class="text-muted d-block small">Data de Entrada</span>
                                    <strong
                                        class="text-dark d-block mt-1">{{ $vehicle->created_at->format('d/m/Y \à\s H:i') }}</strong>
                                </div>
                            </div>

                            {{-- Zona do Gestor da Fotografia (Multi-upload Integrado) --}}
                            <div class="card border bg-light-subtle rounded-3 mb-3">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="fs-3 text-secondary">
                                            <i
                                                class="bi {{ $temFotoReal ? 'bi-image-fill text-success' : 'bi-camera-fill' }}"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">Galeria de Imagens (Máx. 5)</h6>
                                            <small class="text-muted">
                                                {{ $temFotoReal ? 'A viatura tem ' . count($galeriaFinal) . ' fotos associadas.' : 'A usar imagem genérica da marca' }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        @if (!$temFotoReal || count($galeriaFinal) < 5)
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="document.getElementById('quick-photo-input').click();">
                                                <i class="bi bi-upload"></i> Adicionar Foto
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled
                                                title="Limite de 5 fotografias atingido">
                                                <i class="bi bi-check-all"></i> Galeria Cheia
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Rodapé de Ações --}}
                    <div class="card-footer bg-light border-top p-3 d-flex justify-content-between align-items-center">
                        <small class="text-muted font-monospace">ID do Registo: #{{ $vehicle->id }}</small>
                        <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-warning text-white px-4">
                            <i class="bi bi-pencil-square"></i> Editar Dados
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulário Invisível com Compressão Automática no Cliente --}}
    <form id="quick-photo-form" action="{{ route('vehicles.upload_photo', $vehicle->id) }}" method="POST"
        enctype="multipart/form-data" class="d-none">
        @csrf
        <input type="file" name="photo" id="quick-photo-input" accept="image/*" onchange="processAndSubmitPhoto(this)">
    </form>

    {{-- Elemento Estrutural do Lightbox com Setas e botão de eliminação dinâmico --}}
    <div id="custom-lightbox" class="lightbox-overlay d-none">
        <span class="lightbox-close">&times;</span>

        @if ($temFotoReal && count($galeriaFinal) > 1)
            <button class="lightbox-nav btn-prev">&lt;</button>
        @endif

        <div class="lightbox-content-container position-relative">
            <img id="lightbox-img" src="" alt="Zoom Viatura">

            <div class="d-flex justify-content-between align-items-center w-100 mt-3 px-2">
                <div id="lightbox-counter" class="text-white font-monospace small"></div>

                {{-- Botão de apagar a foto específica que está aberta no momento --}}
                @if ($temFotoReal)
                    <form id="delete-photo-form" action="{{ route('vehicles.delete_photo', $vehicle->id) }}"
                        method="POST"
                        onsubmit="return confirm('Tem a certeza que deseja eliminar esta fotografia específica da galeria?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="photo_url" id="photo-url-to-delete" value="">
                        <button type="submit" class="btn btn-sm btn-danger py-1 px-3">
                            <i class="bi bi-trash3"></i> Eliminar Esta Foto
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if ($temFotoReal && count($galeriaFinal) > 1)
            <button class="lightbox-nav btn-next">&gt;</button>
        @endif
    </div>

    {{-- Estilos Isolados do Lightbox Carrossel Real --}}
    <style>
        .lightbox-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95) !important;
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .lightbox-content-container {
            max-width: 75%;
            max-height: 85%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .lightbox-content-container img {
            max-width: 100%;
            max-height: 75vh;
            object-fit: contain;
            box-shadow: 0px 4px 25px rgba(0, 0, 0, 0.9);
            border-radius: 4px;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #fff;
            font-size: 45px;
            font-weight: bold;
            cursor: pointer;
            z-index: 100000;
        }

        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            font-size: 30px;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            z-index: 100000;
            transition: background 0.2s;
        }

        .lightbox-nav:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .btn-prev {
            left: 30px;
        }

        .btn-next {
            right: 30px;
        }

        .transition-smooth {
            transition: all 0.25s ease-in-out !important;
        }

        .btn-venda-custom {
                    background-color: #ffffff !important;
                    transition: all 0.2s ease-in-out !important;
                }

                /* Permite que o Bootstrap faça a inversão corretamente no hover */
                .btn-venda-custom:hover {
                    background-color: #198754 !important; /* Cor verde padrão do success */
                    color: #ffffff !important;
                }
    </style>

    {{-- Scripts Atualizados --}}
    <script>
        function processAndSubmitPhoto(input) {
            if (!input.files || !input.files[0]) return;

            const file = input.files[0];
            const reader = new FileReader();
            document.body.style.cursor = 'wait';

            reader.onload = function(e) {
                const img = new Image();
                img.src = e.target.result;
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const maxW = 1200; // Alinhado com a qualidade do create
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

                    canvas.toBlob(function(blob) {
                        const resizedFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".jpg", {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });

                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(resizedFile);
                        input.files = dataTransfer.files;

                        document.getElementById('quick-photo-form').submit();
                    }, 'image/jpeg', 0.75);
                };
            };
            reader.readAsDataURL(file);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const imagens = @json($galeriaFinal);
            const temFotoReal = @json($temFotoReal);
            let indexAtual = 0;

            const trigger = document.getElementById('lightbox-trigger');
            const lightbox = document.getElementById('custom-lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            const counter = document.getElementById('lightbox-counter');
            const deleteInput = document.getElementById('photo-url-to-delete');
            const closeBtn = document.querySelector('.lightbox-close');
            const btnPrev = document.querySelector('.btn-prev');
            const btnNext = document.querySelector('.btn-next');

            if (trigger && lightbox && lightboxImg) {
                trigger.addEventListener('click', function() {
                    indexAtual = 0;
                    atualizarLightbox();
                    lightbox.classList.remove('d-none');
                    document.body.style.overflow = 'hidden';
                });

                function atualizarLightbox() {
                    const urlCompleta = imagens[indexAtual];
                    lightboxImg.src = urlCompleta;

                    if (counter) {
                        counter.textContent = temFotoReal ? `Foto ${indexAtual + 1} de ${imagens.length}` :
                            'Imagem Padrão da Marca';
                    }

                    if (deleteInput) {
                        deleteInput.value = urlCompleta;
                    }
                }

                if (btnPrev && temFotoReal) {
                    btnPrev.addEventListener('click', function(e) {
                        e.stopPropagation();
                        indexAtual = (indexAtual === 0) ? imagens.length - 1 : indexAtual - 1;
                        atualizarLightbox();
                    });
                }

                if (btnNext && temFotoReal) {
                    btnNext.addEventListener('click', function(e) {
                        e.stopPropagation();
                        indexAtual = (indexAtual === imagens.length - 1) ? 0 : indexAtual + 1;
                        atualizarLightbox();
                    });
                }

                if (closeBtn) closeBtn.addEventListener('click', closeLightbox);

                lightbox.addEventListener('click', function(e) {
                    if (e.target === lightbox || e.target.classList.contains(
                        'lightbox-content-container')) {
                        closeLightbox();
                    }
                });

                document.addEventListener('keydown', function(e) {
                    if (lightbox.classList.contains('d-none')) return;
                    if (e.key === 'Escape') closeLightbox();
                    if (e.key === 'ArrowRight' && btnNext && temFotoReal) btnNext.click();
                    if (e.key === 'ArrowLeft' && btnPrev && temFotoReal) btnPrev.click();
                });
            }

            function closeLightbox() {
                lightbox.classList.add('d-none');
                document.body.style.overflow = 'auto';
                lightboxImg.src = '';
            }
        });
    </script>
@endsection
