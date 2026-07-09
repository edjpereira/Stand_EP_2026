@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registar Nova Viatura</h2>
    <hr>
    {{-- Adicionado o ID para controlo via JS --}}
    <form id="vehicle-create-form" action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            {{-- Dropdown de Marcas Corrigido --}}
            <div class="col-md-4">
                <label class="form-label">Marca</label>
                <select name="make" class="form-select @error('make') is-invalid @enderror">
                    <option value="">Selecione uma marca...</option>
                    @foreach([
                        'Audi',
                        'BMW',
                        'BYD',
                        'Ferrari',
                        'Hyundai',
                        'Kia',
                        'Mercedes-Benz',
                        'Peugeot',
                        'Porsche',
                        'Renault',
                        'Volkswagen'
                    ] as $brand)
                        <option value="{{ $brand }}" {{ old('make') == $brand ? 'selected' : '' }}>
                            {{ $brand }}
                        </option>
                    @endforeach
                </select>
                @error('make') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Modelo</label>
                <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model') }}">
                @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Matrícula</label>
                <input type="text" name="plate" class="form-control @error('plate') is-invalid @enderror" value="{{ old('plate') }}" placeholder="AA-00-AA">
                @error('plate') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Ano</label>
                <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', date('Y')) }}">
                @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Quilómetros</label>
                <input type="number" name="mileage" class="form-control @error('mileage') is-invalid @enderror" value="{{ old('mileage', 0) }}">
                @error('mileage') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Preço (€)</label>
                <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Disponível</option>
                    <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Vendido</option>
                </select>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8">
                <label class="form-label">Fotografia da Viatura</label>
                {{-- Adicionado o ID e o evento onchange para compressão instantânea --}}
                <input type="file" name="photo" id="vehicle-photo-input" class="form-control @error('photo') is-invalid @enderror" accept="image/*" onchange="compressVehiclePhoto(this)">
                @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div id="compression-loader" class="form-text text-primary d-none mt-1">
                    <i class="bi bi-hourglass-split"></i> A otimizar imagem para upload rápido...
                </div>
            </div>
            {{-- Div de visualização da imagem escolhida --}}
            <div class="col-md-4 text-center">
                <div id="preview-container" class="d-none">
                    <label class="form-label d-block">Pré-visualização</label>
                    <img id="photo-preview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 120px; object-fit: cover;">
                </div>
            </div>
        </div>

        <button type="submit" id="btn-submit-vehicle" class="btn btn-success">Guardar Viatura</button>
        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
function compressVehiclePhoto(input) {
    if (!input.files || !input.files[0]) return;

    const file = input.files[0];
    const reader = new FileReader();
    const loader = document.getElementById('compression-loader');
    const btnSubmit = document.getElementById('btn-submit-vehicle');
    const previewContainer = document.getElementById('preview-container');
    const photoPreview = document.getElementById('photo-preview');

    // Bloqueia interações e avisa o utilizador
    loader.classList.remove('d-none');
    btnSubmit.disabled = true;
    document.body.style.cursor = 'wait';

    reader.onload = function (e) {
        // Mostra o preview instantâneo na View
        photoPreview.src = e.target.result;
        previewContainer.classList.remove('d-none');

        const img = new Image();
        img.src = e.target.result;
        img.onload = function () {
            const canvas = document.createElement('canvas');
            const maxW = 1200; // Resolução excelente para web mantendo ficheiro levíssimo
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
                // Converte em ficheiro JPEG otimizado
                const resizedFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".jpg", {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });

                // Substitui o ficheiro massivo do input pelo binário leve comprimido
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(resizedFile);
                input.files = dataTransfer.files;

                // Liberta o formulário para gravação
                loader.classList.add('d-none');
                btnSubmit.disabled = false;
                document.body.style.cursor = 'default';
            }, 'image/jpeg', 0.75); // 75% de qualidade remove metadados pesados mantendo nitidez visual
        };
    };
    reader.readAsDataURL(file);
}
</script>
@endsection
