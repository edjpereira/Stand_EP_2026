@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center my-4">
        <div class="col-md-7">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-dark text-white p-4 rounded-top-4 border-0">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-gear me-2 text-primary"></i>Configurações do Perfil</h5>
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

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3 border">
                            <img src="{{ $user->photo_url }}" alt="Atual" class="rounded-circle border border-primary shadow-sm me-3" style="width: 70px; height: 70px; object-fit: cover;">
                            <div>
                                <label for="photo" class="form-label fw-bold mb-1">Foto de Perfil <span class="text-muted small fw-normal">(Opcional)</span></label>
                                <input type="file" class="form-control form-control-sm" id="photo" name="photo">
                                <small class="text-muted d-block mt-1">Formatos aceites: JPG, PNG, WEBP (Máx: 2MB)</small>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold text-dark">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold text-dark">Endereço de E-mail <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold text-dark">Número de Telemóvel <span class="text-muted small fw-normal">(Opcional)</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Ex: 912345678">
                            </div>
                            <div class="col-md-6">
                                <label for="birthday" class="form-label fw-bold text-dark">Data de Nascimento <span class="text-muted small fw-normal">(Opcional)</span></label>
                                <input type="date" class="form-control" id="birthday" name="birthday" value="{{ old('birthday', $user->birthday) }}">
                            </div>
                        </div>

                        <div class="p-3 bg-light bg-opacity-50 rounded-3 border mb-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock me-2 text-warning"></i>Alterar Password <span class="text-muted small fw-normal">(Deixar em branco para manter a atual)</span></h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label small text-muted">Nova Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label small text-muted">Confirmar Nova Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('profile.show') }}" class="btn btn-light text-muted">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">Gravar Alterações</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
