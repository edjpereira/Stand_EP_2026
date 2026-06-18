@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-journal-check" style="color: #00D4FF;"></i> Registo de Auditoria</h2>
        <a href="{{ route('home') }}" class="btn btn-secondary">Voltar à Dashboard</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Data</th>
                        <th>Utilizador</th>
                        <th>Ação</th>
                        <th>Objeto</th>
                        <th>ID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                    <tr>
                        <td class="text-muted small">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                        <td class="fw-bold">{{ $activity->causer->name ?? 'Sistema' }}</td>
                        <td>
                            <span class="badge bg-info text-dark">{{ ucfirst($activity->description) }}</span>
                        </td>
                        <td>{{ class_basename($activity->subject_type) }}</td>
                        <td>{{ $activity->subject_id }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection
