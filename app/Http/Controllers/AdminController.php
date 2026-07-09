<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\AuditLog;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin-only');
    }

    public function unifiedTrash()
    {
        $this->authorize('admin-only');

        $deletedClients = \App\Models\Client::onlyTrashed()->get();
        $deletedVehicles = \App\Models\Vehicle::onlyTrashed()->get();

        return view('admin.trash', compact('deletedClients', 'deletedVehicles'));
    }

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

    public function restoreVehicle($id)
    {
        Vehicle::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Viatura restaurada.');
    }


    public function forceDeleteVehicle($id)
{
    $vehicle = \App\Models\Vehicle::withTrashed()->findOrFail($id);

    $vehicle->forceDelete();

    return redirect()->route('vehicles.index')->with('success', 'Viatura eliminada permanentemente!');
}
}
