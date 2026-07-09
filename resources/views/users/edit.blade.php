@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center my-4">
            <div class="col-md-7">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-dark text-white p-4 rounded-top-4 border-0">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-person-gear me-2 text-primary"></i>Configurações do Perfil
                        </h5>
                    </div>
                    <div class="card-body p-4">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3 border">
                                {{-- Bloco da Foto + Botão de Eliminar (empilhados verticalmente à esquerda) --}}
                                <div class="d-flex flex-column align-items-center me-3">
                                    <img src="{{ $user->photo_url }}" alt="Atual"
                                        class="rounded-circle border border-primary shadow-sm"
                                        style="width: 70px; height: 70px; object-fit: cover;">

                                    {{-- Botão de Lixo (Apenas aparece se o utilizador tiver de facto um ficheiro de imagem na BD) --}}
                                    @if ($user->photo)
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-0 mt-2"
                                            style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                            data-bs-toggle="tooltip" title="Eliminar Foto"
                                            onclick="if(confirm('Tem a certeza que deseja remover a sua foto de perfil?')) { document.getElementById('delete-photo-form').submit(); }">
                                            <i class="bi bi-trash3" style="font-size: 0.85rem;"></i>
                                        </button>
                                    @endif
                                </div>

                                <div class="flex-grow-1">
                                    <label for="photo" class="form-label">Foto de Perfil</label>
                                    <div class="input-group">
                                        <input type="file" name="photo" id="photo"
                                            class="form-control @error('photo') is-invalid @enderror"
                                            data-browse="Procurar">
                                    </div>
                                    <div class="form-text">Formatos aceitáveis: jpeg, png, jpg, webp (Máx: 2MB)</div>

                                    @error('photo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold text-dark">Nome <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold text-dark">Endereço de E-mail <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-bold text-dark">Número de Telemóvel <span
                                            class="text-muted small fw-normal">(Opcional)</span></label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{ old('phone', $user->phone) }}" placeholder="Ex: 912345678">
                                </div>
                                <div class="col-md-6">
                                    <label for="birthday" class="form-label fw-bold">Data de Nascimento <span
                                            class="text-muted small fw-normal">(Opcional)</span></label>
                                    <input type="date" name="birthday" id="birthday"
                                        class="form-control @error('birthday') is-invalid @enderror"
                                        value="{{ old('birthday', $user->birthday ? $user->birthday->format('Y-m-d') : '') }}">

                                    @error('birthday')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="p-3 bg-light bg-opacity-50 rounded-3 border mb-4">
                                <h6 class="fw-bold text-dark mb-3"><i
                                        class="bi bi-shield-lock me-2 text-warning"></i>Alterar Password <span
                                        class="text-muted small fw-normal">(Deixar em branco para manter a actual)</span>
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label small text-muted">Nova Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label small text-muted">Confirmar
                                            Nova Password</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation">
                                    </div>
                                </div>
                            </div>

                            {{-- ZONA DO PEDIDO DE ALTERAÇÃO DE NÍVEL --}}
                            <div class="p-3 bg-light bg-opacity-50 rounded-3 border mb-4">
                                <h6 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-shield-check me-2 text-secondary"></i>Nível de Acesso ao Sistema
                                </h6>

                                @if ($user->role === 'admin')
                                    <div class="text-success small fw-bold d-flex align-items-center mt-2">
                                        <i class="bi bi-check-circle-fill me-2"></i> Esta conta possui privilégios de
                                        Administrador.
                                    </div>
                                @elseif($user->admin_request_status === 'pending')
                                    <div
                                        class="alert alert-warning mb-0 d-flex align-items-center justify-content-between p-2 mt-2 small border-0">
                                        <span><i class="bi bi-hourglass-split me-2"></i> O pedido de acesso Admin está
                                            a aguardar revisão.</span>
                                        <span class="badge bg-warning text-dark px-3 py-1">Pendente</span>
                                    </div>
                                @else
                                    {{-- Se for null, o utilizador pode submeter um novo pedido --}}
                                    <div class="d-flex align-items-center justify-content-between mt-2">
                                        <span class="text-muted small">O nível actual de acesso é de utilizador padrão.</span>
                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('form-request-admin').submit();"
                                            class="btn btn-sm btn-warning fw-bold text-dark shadow-sm text-decoration-none">
                                            <i class="bi bi-arrow-up-circle me-1"></i> Solicitar Nível Admin
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </form> {{-- FIM DO FORMULÁRIO PRINCIPAL DE EDIT --}}

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FORMULÁRIOS AUXILIARES INDEPENDENTES (Ficam fora do formulário principal, sem o PUT a chatear) --}}
    <form id="form-request-admin" action="{{ route('users.request_admin', $user->id) }}" method="POST" class="d-none">
        @csrf
    </form>

    @if ($user->photo)
        <form id="delete-photo-form" action="{{ route('users.delete-photo', $user->id) }}" method="POST"
            class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endif
    </form> {{-- FIM DO FORMULÁRIO PRINCIPAL DE EDIT --}}

    </div> {{-- Fim do card-body --}}
    </div> {{-- Fim do card --}}
    </div> {{-- Fim da col-md-7 --}}
    </div> {{-- Fim da row --}}
    </div> {{-- Fim do container --}}

    {{-- FORMULÁRIOS AUXILIARES INDEPENDENTES (Completamente isolados no fundo) --}}
    <form id="form-request-admin" action="{{ route('users.request_admin', $user->id) }}" method="POST" class="d-none">
        @csrf
    </form>

    <form id="form-dismiss-notification" action="{{ route('users.dismiss_notification', $user->id) }}" method="POST"
        class="d-none">
        @csrf
    </form>

    @if ($user->photo)
        <form id="delete-photo-form" action="{{ route('users.delete-photo', $user->id) }}" method="POST"
            class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endif

    <style>
        input[type="file"]::file-selector-button {
            content: "Procurar..." !important;
        }

        input[type="file"]::before {
            content: "Nenhum ficheiro selecionado";
        }
    </style>
@endsection
