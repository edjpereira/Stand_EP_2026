@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <div class="row justify-content-center my-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body text-center p-5">
                    <div class="position-relative d-inline-block mb-3">
                        <img src="{{ $user->photo_url }}" alt="Foto de Perfil" class="rounded-circle img-thumbnail border-2 border-primary shadow" style="width: 140px; height: 140px; object-fit: cover;">
                        @if($user->role === 'admin')
                            <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-danger px-2 py-1 shadow" style="font-size: 0.75rem;">ADMIN</span>
                        @endif
                    </div>

                    <h3 class="fw-bold text-dark mb-1">
                        {{ $user->name }}
                        @if($user->role === 'admin')
                            <small class="text-danger d-block fs-6 mt-1 fw-semibold">[Administrador do Sistema]</small>
                        @endif
                    </h3>
                    <p class="text-muted mb-4">{{ $user->email }}</p>

                    <hr class="text-muted my-4">

                    <div class="text-start mb-4">
                        <div class="mb-3 d-flex align-items-center">
                            <i class="bi bi-telephone text-primary me-3 fs-5"></i>
                            <div>
                                <small class="text-muted d-block">Telemóvel</small>
                                <span class="fw-medium text-dark">{{ $user->phone ?? 'Não associado' }}</span>
                            </div>
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <i class="bi bi-cake2 text-primary me-3 fs-5"></i>
                            <div>
                                <small class="text-muted d-block">Data de Nascimento</small>
                                <span class="fw-medium text-dark">
                                    {{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') : 'Não informada' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-3 shadow-sm">
                            <i class="bi bi-pencil-square me-2"></i>Editar o meu Perfil
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-light rounded-3 text-muted">Voltar ao Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
