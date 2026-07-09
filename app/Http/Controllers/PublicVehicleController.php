<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class PublicVehicleController extends Controller
{
    public function index(Request $request)
{
    $vehicles = Vehicle::where('status', 'available')
        ->orderBy('id', 'desc')
        ->paginate(10);

    if ($request->ajax()) {
        return view('vehicles.partials.vehicle_cards', compact('vehicles'))->render();
    }

    return view('public.index', compact('vehicles'));
}
}
