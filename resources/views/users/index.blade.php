@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Gestão de Utilizadores</h2>
            <div class="d-flex gap-2">
                @can('admin-only')
                    <a href="{{ route('users.create') }}" class="btn btn-success">Novo Utilizador</a>
                @endcan
                <a href="{{ route('home_dashboard') }}" class="btn btn-primary">Voltar à Dashboard</a>
            </div>
        </div>
        <hr>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (auth()->user()->role === 'admin')
            @php
                // Procura utilizadores com pedidos pendentes
                $pedidosPendentes = \App\Models\User::where('admin_request_status', 'pending')->get();
            @endphp

            @if ($pedidosPendentes->isNotEmpty())
                <div class="card border-warning shadow-sm mb-4">
                    <div class="card-header bg-warning-subtle fw-bold text-dark py-2">
                        <i class="bi bi-bell-fill me-2 text-warning"></i> Pedidos de Alteração de Nível Pendentes
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($pedidosPendentes as $pedido)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2 bg-white">
                                <span class="text-dark">
                                    <strong>{{ $pedido->name }}</strong> (ID: #{{ $pedido->id }}) solicitou acesso ao
                                    nível Admin.
                                </span>
                                <div class="d-flex gap-2">
                                    {{-- Botão Aceitar --}}
                                    <form action="{{ route('admin.users.handle_request', [$pedido->id, 'approve']) }}"
                                        method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success px-3 fw-bold">
                                            <i class="bi bi-check-lg me-1"></i> Aceitar
                                        </button>
                                    </form>

                                    {{-- Botão Rejeitar --}}
                                    <form action="{{ route('admin.users.handle_request', [$pedido->id, 'reject']) }}"
                                        method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger px-3 fw-bold">
                                            <i class="bi bi-x-lg me-1"></i> Rejeitar
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Lista de Utilizadores</h4>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Nível</th>
                            <th>Último Acesso</th>
                            @if (auth()->user()->role === 'admin' || $users->contains('id', auth()->id()))
                                <th class="text-center" style="width: 160px;">Ações</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @php
                                $isAdmin = auth()->user()->role === 'admin';
                                $isOwnProfile = $user->id === auth()->id();
                            @endphp

                            @if ($isAdmin || $isOwnProfile)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-primary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($user->last_login_at)
                                            {{ \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted small">Nunca acedeu</span>
                                        @endif
                                    </td>

                                    <td class="text-center text-nowrap style-actions-cell" style="width: 160px;">
                                        <div class="btn-group shadow-premium-vehicle" role="group"
                                            aria-label="Ações de Utilizador">

                                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-vehicle-group"
                                                data-bs-toggle="tooltip" title="Ver Detalhes do Utilizador">
                                                <i class="bi bi-eye text-secondary"></i>
                                            </a>

                                            @if ($isAdmin || $isOwnProfile)
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="btn btn-vehicle-group" data-bs-toggle="tooltip"
                                                    title="Editar Utilizador">
                                                    <i class="bi bi-pencil text-dark"></i>
                                                </a>
                                            @endif

                                            @can('admin-only')
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')

                                                    @if ($isOwnProfile)
                                                        <span data-bs-toggle="tooltip"
                                                            title="A eliminação da própria conta não é permitida"
                                                            class="d-inline-block">
                                                            <button type="submit"
                                                                class="btn btn-vehicle-group btn-vehicle-danger" disabled>
                                                                <i class="bi bi-trash3 text-danger"></i>
                                                            </button>
                                                        </span>
                                                    @else
                                                        <button type="submit" class="btn btn-vehicle-group btn-vehicle-danger"
                                                            onclick="return confirm('Tem a certeza que deseja eliminar permanentemente este utilizador?')"
                                                            data-bs-toggle="tooltip" title="Eliminar Utilizador">
                                                            <i class="bi bi-trash3 text-danger"></i>
                                                        </button>
                                                    @endif
                                                </form>
                                            @endcan

                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .shadow-premium-vehicle {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.330), 0 1px 4px rgba(0, 0, 0, 0.330) !important;
            border-radius: 20px;
            display: inline-flex;
        }

        .btn-vehicle-group {
            width: 47px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background-color: #ffffff !important;
            transition: all 0.15s ease-in-out;
            margin: 0;
        }

        .btn-vehicle-group i {
            font-size: 1.05rem;
            line-height: 1;
        }

        .btn-vehicle-group:not(:first-child) {
            border-left: 1px solid #e2e8f0 !important;
        }

        .btn-vehicle-group:hover {
            background-color: #f8f9fa !important;
        }

        .btn-vehicle-danger:hover {
            background-color: rgba(220, 53, 69, 0.08) !important;
        }

        .btn-vehicle-danger:hover i {
            color: #bb2d3b !important;
        }

        .btn-group>.btn-vehicle-group:first-child {
            border-top-left-radius: 20px !important;
            border-bottom-left-radius: 20px !important;
        }

        .btn-group>form .btn-vehicle-group,
        .btn-group>form span .btn-vehicle-group {
            border-radius: 0 !important;
        }

        .btn-group>form:last-child .btn-vehicle-group,
        .btn-group>form:last-child span .btn-vehicle-group {
            border-top-right-radius: 20px !important;
            border-bottom-right-radius: 20px !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        .btn-vehicle-group:disabled,
        .btn-vehicle-group[disabled] {
            background-color: #f8f9fa !important;
            border-color: #e2e8f0 !important;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .btn-vehicle-group:disabled i,
        .btn-vehicle-group[disabled] i {
            color: #a0aec0 !important;
        }
    </style>
@endsection
