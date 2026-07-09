@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center my-4">
            <div class="col-md-7">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-dark text-white p-4 rounded-top-4 border-0">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-person me-2 text-primary"></i>Detalhes do Utilizador
                        </h5>
                    </div>
                    <div class="card-body p-4">

                        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3 border">
                            <div class="d-flex flex-column align-items-center me-3">
                                <img src="{{ $user->photo_url }}" alt="Atual"
                                    class="rounded-circle border border-primary shadow-sm"
                                    style="width: 70px; height: 70px; object-fit: cover;">
                            </div>

                            <div class="flex-grow-1">
                                <label for="photo" class="form-label">Foto de Perfil</label>
                                <div class="input-group">
                                    <input type="file" name="photo" id="photo" class="form-control" disabled>
                                </div>
                                <div class="form-text">Visualização de perfil trancada.</div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold text-dark">Nome</label>
                                <input type="text" class="form-control bg-light" id="name" value="{{ $user->name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold text-dark">Endereço de E-mail</label>
                                <input type="email" class="form-control bg-light" id="email" value="{{ $user->email }}" readonly>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold text-dark">Número de Telemóvel</label>
                                <input type="text" class="form-control bg-light" id="phone" value="{{ $user->phone ?? 'Não associado' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="birthday" class="form-label fw-bold text-dark">Data de Nascimento</label>
                                <input type="date" id="birthday" class="form-control bg-light"
                                    value="{{ $user->birthday ? $user->birthday->format('Y-m-d') : '' }}" disabled>
                            </div>
                        </div>

                        {{-- Zona de Pedido de Nível Admin --}}
                        <hr class="my-4">
                        <div class="card bg-light border rounded-3 mb-4">
                            <div class="card-body p-3">
                                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-lock me-2 text-warning"></i>Nível de Acesso</h6>

                                @if($user->role === 'admin')
                                    <span class="badge bg-success p-2"><i class="bi bi-check-circle me-1"></i> Conta de Administrador</span>
                                @elseif($user->admin_request_status === 'pending')
                                    <div class="alert alert-warning mb-0 d-flex align-items-center justify-content-between p-2 small">
                                        <span><i class="bi bi-hourglass-split me-1"></i> Aguardar validação do pedido pelos Admins.</span>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>Pendente</button>
                                    </div>
                                @elseif($user->admin_request_status === 'rejected')
                                    <div class="alert alert-danger mb-0 d-flex align-items-center justify-content-between p-2 small">
                                        <span><i class="bi bi-exclamation-triangle me-1"></i> O teu pedido para Admin foi recusado.</span>
                                        <form action="{{ route('users.dismiss_notification', $user->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger px-3 fw-bold">Aceito</button>
                                        </form>
                                    </div>
                                @else
                                    {{-- Caso não tenha pedido feito nem seja admin --}}
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-muted small">Actualmente, esta conta NÃO possui permissões de Administrador.</span>
                                        <form action="{{ route('users.request_admin', $user->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning fw-bold text-dark">
                                                <i class="bi bi-arrow-up-circle me-1"></i> Solicitar Nível Admin
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Botões de Ação Originais --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-light text-muted">Voltar à Lista</a>
                            <a href="{{ route('users.edit', $user->id ?? auth()->id()) }}" class="btn btn-primary">
                                <i class="bi bi-pencil-square me-1"></i>Editar Dados
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
