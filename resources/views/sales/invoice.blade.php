<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Fatura Nº {{ $sale->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; font-size: 12px; line-height: 1.6; }
        .invoice-box { max-width: 800px; margin: auto; padding: 10px; }

        /* Topo / Cabeçalho */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .logo { font-size: 24px; font-weight: bold; color: #1a1a1a; text-transform: uppercase; letter-spacing: 1px; }
        .company-info { text-align: right; color: #666; font-size: 11px; }

        /* Secção de Entidades (Stand vs Cliente) */
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .details-table td { width: 50%; vertical-align: top; }
        .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #888; margin-bottom: 5px; border-bottom: 1px solid #ddd; padding-bottom: 3px; width: 90%; }
        .entity-details { line-height: 1.4; }

        /* Dados do Documento */
        .doc-details { margin-bottom: 30px; font-size: 11px; color: #444; }

        /* Tabela de Produtos/Automóvel */
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th { background: #f8f9fa; text-align: left; padding: 10px; font-size: 11px; text-transform: uppercase; color: #565656; border-bottom: 2px solid #ddd; }
        .items-table td { padding: 15px 10px; vertical-align: top; border-bottom: 1px solid #eee; }

        /* Regras estritas pedidas pelo utilizador */
        .car-main { font-size: 16px; font-weight: bold; color: #000; }
        .car-sub { font-size: 11px; color: #555; margin-top: 4px; font-weight: 500; }
        .car-mileage { font-size: 10px; color: #777; margin-top: 2px; }
        .price-col { text-align: right; font-size: 16px; font-weight: bold; color: #000; vertical-align: middle; }

        /* Totais */
        .total-box { float: right; width: 40%; margin-top: 30px; border-top: 2px solid #333; padding-top: 10px; text-align: right; }
        .total-title { font-size: 12px; text-transform: uppercase; color: #666; display: inline-block; }
        .total-amount { font-size: 20px; font-weight: bold; color: #198754; margin-top: 5px; }
    </style>
</head>
<body>

<div class="invoice-box">
    <table class="header-table">
        <tr>
            <td class="logo">
                Stand Eduardo Pereira
            </td>
            <td class="company-info">
                <strong>Stand Eduardo Pereira, Lda.</strong><br>
                Rua de Camões, nº 145, 4700-144 Braga<br>
                NIF: PT999A888B9<br>
                Email: financeiro@eduardopereira.pt
            </td>
        </tr>
    </table>

    <div class="doc-details">
        <strong>FATURA Nº:</strong> FT/{{ date('Y') }}/{{ $sale->id }}<br>
        <strong>DATA DE EMISSÃO:</strong> {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}
    </div>

    <!-- Dados Intervenientes -->
    <table class="details-table">
        <tr>
            <td>
                <div class="section-title">De (Vendedor)</div>
                <div class="entity-details">
                    <strong>Stand Eduardo Pereira</strong><br>
                    Rua de Camões, nº 145<br>
                    4700-144 Braga, Portugal
                </div>
            </td>
            <td>
                <div class="section-title">Para (Cliente)</div>
                <div class="entity-details">
                    <strong>{{ $sale->client->name }}</strong><br>
                    {{ $sale->client->address ?? 'Morada não registada' }}<br>
                    NIF: {{ $sale->client->taxId ?? '999999999' }}
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Descrição do Veículo</th>
                <th style="text-align: right;">Preço Líquido</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="car-main">{{ $sale->vehicle->make }} {{ $sale->vehicle->model }}</div>

                    <div class="car-sub">Matrícula: {{ $sale->vehicle->plate }}</div>

                    <div class="car-mileage">Quilometragem: {{ number_format($sale->vehicle->mileage, 0, ',', '.') }} km</div>
                </td>
                <td class="price-col">
                    {{ number_format($sale->sale_amount, 2, ',', '.') }} €
                </td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-title">Total da Venda</div>
        <div class="total-amount">{{ number_format($sale->sale_amount, 2, ',', '.') }} €</div>
    </div>
</div>

</body>
</html>
