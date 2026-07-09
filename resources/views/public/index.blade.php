@extends('layouts.app')

@section('content')
<style>
    nav.navbar.border-bottom { display: none !important; }
    main.py-4 { padding-top: 0 !important; padding-bottom: 0 !important; }
    body {
        background-image: url("{{ asset('images/bg.jpeg') }}") !important;
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        background-attachment: fixed !important;
    }
    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.6);
        z-index: -1;
        pointer-events: none;
    }
    .glass-panel {
        background-color: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(8px);
    }
    .vehicle-card {
        box-shadow: 3px 12px 30px rgba(0, 0, 0, 0.25) !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .vehicle-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35) !important;
    }
</style>

<div class="container pt-4 pb-5">
    <div class="card glass-panel border-0 shadow-sm p-4 mb-4 rounded-3">
        <h1 class="h3 mb-0 fw-bold text-dark">As Nossas Viaturas</h1>
    </div>

    <div id="vehicles-container" class="d-flex flex-column gap-3">
        @include('vehicles.partials.vehicle_cards')
    </div>

    <div class="text-center mt-5" id="load-more-section">
        @if($vehicles->hasMorePages())
            <button id="btn-load-more" data-next-page="{{ $vehicles->currentPage() + 1 }}" class="btn btn-primary btn-lg px-5 shadow">
                <i class="bi bi-plus-circle me-2"></i> Próximas 10 viaturas
            </button>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnLoadMore = document.getElementById('btn-load-more');
        const container = document.getElementById('vehicles-container');
        const section = document.getElementById('load-more-section');

        if (!btnLoadMore) return;

        btnLoadMore.addEventListener('click', function () {
            let nextPage = this.getAttribute('data-next-page');
            let currentText = this.innerHTML;

            // Feedback visual de carregamento
            this.disabled = true;
            this.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> A carregar...`;

            fetch(`?page=${nextPage}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                if (html.trim() === '') {
                    section.innerHTML = '<p class="text-muted small">Não existem mais viaturas para exibir.</p>';
                    return;
                }

                container.insertAdjacentHTML('beforeend', html);

                let newPage = parseInt(nextPage) + 1;
                btnLoadMore.setAttribute('data-next-page', newPage);

                btnLoadMore.disabled = false;
                btnLoadMore.innerHTML = currentText;
            })
            .catch(error => {
                console.error('Erro ao carregar mais viaturas:', error);
                btnLoadMore.disabled = false;
                btnLoadMore.innerHTML = 'Erro ao carregar. Tente novamente.';
            });
        });
    });
</script>
@endsection
