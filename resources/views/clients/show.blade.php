@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalhes do Cliente</h2>
    <hr>
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h4>{{ $client->name }}</h4>
        </div>
        <div class="card-body">
            <p><strong>ID Interno:</strong> {{ $client->id }}</p>
            <p><strong>Email:</strong> {{ $client->email }}</p>
            <p><strong>Telefone:</strong> {{ $client->phone }}</p>
            <p><strong>Tax ID (NIF):</strong> {{ $client->tax_id }}</p>
            <p><strong>Morada:</strong> {{ $client->address }}</p>
            <p><strong>Data de Registo:</strong> {{ $client->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning text-white">Editar</a>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Voltar à Listagem</a>
        </div>
    </div>
</div>
@endsection
