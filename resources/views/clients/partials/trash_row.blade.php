<tr>
    <td class="ps-3 text-truncate">{{ $client->name }}</td>
    <td>{{ $client->taxId ?? '---' }}</td>
    <td class="text-center">
        <button type="button" class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#viewClientModal{{ $client->id }}">
            Ver
        </button>

        <form action="{{ route('clients.restore', $client->id) }}" method="POST" class="d-inline">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-outline-success me-1">Restaurar</button>
        </form>

        <form action="{{ route('clients.force', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apagar definitivamente?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Apagar</button>
        </form>
    </td>
</tr>

{{-- Modal do Cliente --}}
<div class="modal fade" id="viewClientModal{{ $client->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow border-0">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-person-bounding-box me-2 text-secondary"></i> Ficha de Cliente (Reciclado)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center mb-3">
                    <div class="bg-light d-inline-block rounded-circle p-3 mb-2">
                        <i class="bi bi-person text-secondary h3 m-0"></i>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $client->name }}</h4>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 mt-1">ID #{{ $client->id }} — Removido</span>
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2"><span class="text-muted fw-semibold">E-mail:</span><span class="text-dark fw-bold">{{ $client->email }}</span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2"><span class="text-muted fw-semibold">Telefone:</span><span class="text-dark fw-bold">{{ $client->phone ?? '---' }}</span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2"><span class="text-muted fw-semibold">NIF / Contribuinte:</span><span class="badge bg-light text-dark border fw-bold">{{ $client->taxId ?? 'N/A' }}</span></li>
                    <li class="list-group-item d-flex flex-column align-items-start px-0 py-2"><span class="text-muted fw-semibold mb-1">Morada:</span><span class="text-dark fw-medium">{{ $client->address ?? 'Nenhuma morada registada.' }}</span></li>
                    @if(isset($client->deleted_at))
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2"><span class="text-muted fw-semibold">Data de Remoção:</span><span class="text-danger fw-bold">{{ $client->deleted_at->format('d/m/Y H:i') }}</span></li>
                    @endif
                </ul>
            </div>
            <div class="modal-footer border-top-0 pt-0 justify-content-left">
                <form action="{{ route('clients.force', $client->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Apagar definitivamente este cliente do sistema?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3 me-1"></i>Apagar Definitivamente</button>
                </form>
                <button type="button" class="btn btn-sm btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
