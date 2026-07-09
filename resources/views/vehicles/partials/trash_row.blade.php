<tr>
    <td class="ps-3 text-truncate">{{ $vehicle->make }} {{ $vehicle->model }}</td>
    <td><span class="badge bg-light text-dark border">{{ $vehicle->plate }}</span></td>
    <td class="text-center">
        <button type="button" class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#viewVehicleModal{{ $vehicle->id }}">
            Ver
        </button>

        <form action="{{ route('vehicles.restore', $vehicle->id) }}" method="POST" class="d-inline">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-outline-success me-1">Restaurar</button>
        </form>

        <form action="{{ route('vehicles.force', $vehicle->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apagar definitivamente?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Apagar</button>
        </form>
    </td>
</tr>

{{-- Modal do Veículo --}}
<div class="modal fade" id="viewVehicleModal{{ $vehicle->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow border-0">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-car-front-fill me-2 text-secondary"></i> Detalhes da Viatura (Reciclada)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center mb-3">
                    <div class="bg-light d-inline-block rounded-circle p-3 mb-2">
                        <i class="bi bi-car-front text-secondary h3 m-0"></i>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $vehicle->make }} {{ $vehicle->model }}</h4>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 mt-1">ID #{{ $vehicle->id }} — Removida</span>
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2"><span class="text-muted fw-semibold">Marca:</span><span class="text-dark fw-bold">{{ $vehicle->make }}</span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2"><span class="text-muted fw-semibold">Modelo:</span><span class="text-dark fw-bold">{{ $vehicle->model }}</span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2"><span class="text-muted fw-semibold">Matrícula:</span><span class="badge bg-light text-dark border fw-bold">{{ $vehicle->plate }}</span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2"><span class="text-muted fw-semibold">Ano:</span><span class="text-dark fw-bold">{{ $vehicle->year ?? '---' }}</span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2"><span class="text-muted fw-semibold">Preço:</span><span class="text-dark fw-bold">{{ isset($vehicle->price) ? number_format($vehicle->price, 2, ',', '.') . ' €' : '---' }}</span></li>
                    @if(isset($vehicle->deleted_at))
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2"><span class="text-muted fw-semibold">Data de Remoção:</span><span class="text-danger fw-bold">{{ $vehicle->deleted_at->format('d/m/Y H:i') }}</span></li>
                    @endif
                </ul>
            </div>
            <div class="modal-footer border-top-0 pt-0 justify-content-left">
                <form action="{{ route('vehicles.force', $vehicle->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Apagar definitivamente esta viatura do sistema?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3 me-1"></i>Apagar Definitivamente</button>
                </form>
                <button type="button" class="btn btn-sm btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
