<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function __construct()
    {
        // Garante que TODOS os métodos aqui dentro precisam de ser admin
        $this->middleware('can:admin-only');
    }

    // A página centralizada com as abas (a "Trash Box" única)
    public function unifiedTrash()
    {
        $this->authorize('admin-only');

        $deletedClients = \App\Models\Client::onlyTrashed()->get();
        $deletedVehicles = \App\Models\Vehicle::onlyTrashed()->get();

        return view('admin.trash', compact('deletedClients', 'deletedVehicles'));
    }

    // Operações de Clientes
    public function restoreClient($id)
    {
        Client::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Cliente restaurado.');
    }

    public function forceDeleteClient($id)
    {
        Client::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Cliente apagado definitivamente.');
    }

    // Operações de Veículos
    public function restoreVehicle($id)
    {
        Vehicle::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Viatura restaurada.');
    }

    public function forceDeleteVehicle($id)
    {
        Vehicle::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Viatura apagada definitivamente.');
    }
}
