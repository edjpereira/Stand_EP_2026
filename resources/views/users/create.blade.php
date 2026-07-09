@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Criar Novo Utilizador</h2>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar e Voltar</a>
    </div>
    <hr>

    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Dados da Nova Conta</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                {{-- Nome --}}
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label">Endereço de Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Role/Nível de Acesso --}}
                <div class="mb-3">
                    <label class="form-label">Função / Role</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee (Comercial)</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Administrador)</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirmação da Password --}}
                <div class="mb-4">
                    <label class="form-label">Confirmar Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                {{-- Botão de Submissão --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Criar Utilizador</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
