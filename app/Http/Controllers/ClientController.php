<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        // Paginação a 25
        $clients = \App\Models\Client::orderBy('id', 'asc')->paginate(25);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'taxId' => 'required|digits:9|unique:clients,taxId', // Força a coluna real da BD
        ], [
            // Mensagens personalizadas em português
            'name.required' => 'O nome do cliente é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Insira um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já se encontra registado.',
            'taxId.required' => 'O NIF é obrigatório.',
            'taxId.digits' => 'O NIF tem de ter exatamente 9 dígitos.',
            'taxId.unique' => 'Este NIF já se encontra registado no sistema.',
        ]);

        $client = Client::create($validated);

        $perPage = 25;
        $totalClients = Client::count();
        $lastPage = ceil($totalClients / $perPage);

        return redirect()->route('clients.index', ['page' => $lastPage])
            ->with('highlight_id', $client->id)
            ->with('success', 'Cliente criado com sucesso!');

    }

    public function show($id)
    {
        $client = Client::with(['sales.vehicle', 'interactions.vehicle'])->findOrFail($id);
        $vehicles = Vehicle::orderBy('make', 'asc')
                       ->orderBy('model', 'asc')
                       ->get();

        return view('clients.show', compact('client', 'vehicles'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Ignora o ID atual para permitir manter o mesmo email/nif
            // e usa whereNull('deleted_at') para ignorar os "apagados"
            'email' => [
                'required',
                'email',
                Rule::unique('clients', 'email')->ignore($client->id)->whereNull('deleted_at')
            ],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'taxId' => [ // Corrigido para taxId (o nome correto)
                'required',
                'digits:9',
                Rule::unique('clients', 'taxId')->ignore($client->id)->whereNull('deleted_at')
            ],
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    // No ClientController.php
    public function destroy(Client $client)
    {
        // Executa o Soft Delete
        $client->delete();

        // Mensagem diferenciada conforme o cargo
        $mensagem = auth()->user()->role === 'admin'
            ? 'Cliente movido para a reciclagem.'
            : 'Pedido de eliminação do cliente submetido para aprovação.';

        return redirect()->route('clients.index')->with('success', $mensagem);
    }
}
