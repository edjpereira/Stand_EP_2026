<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        // Iniciamos a query
        $query = Vehicle::query();

        // --- Passo 11: Lógica de Pesquisa ---
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('plate', 'like', "%{$search}%");
            });
        }

        // --- Passo 11: Lógica de Ordenação ---
        $allowedSorts = ['id', 'make', 'model', 'year', 'price'];
        $sortBy = $request->input('sort_by', 'id'); // Padrão: id
        $sortOrder = $request->input('sort_order', 'asc'); // Padrão: asc

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $vehicles = $query->get();

        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'plate' => 'required|string|max:20',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'mileage' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
            'status' => 'required|in:available,sold',
        ]);

        // --- Passo 10: Lógica de Upload de Foto ---
        if ($request->hasFile('photo')) {
            // Guarda na pasta storage/app/public/vehicles
            $path = $request->file('photo')->store('vehicles', 'public');
            $validated['photo'] = $path;
        }

        Vehicle::create($validated);

        return redirect()->route('vehicles.index')->with('success', 'Viatura registada com sucesso!');
    }

    public function show(Vehicle $vehicle)
    {
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'plate' => 'required|string|max:20',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'mileage' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:available,sold',
        ]);

        if ($request->hasFile('photo')) {
            // Apaga a foto antiga se ela existir no disco
            if ($vehicle->photo) {
                Storage::disk('public')->delete($vehicle->photo);
            }
            $path = $request->file('photo')->store('vehicles', 'public');
            $validated['photo'] = $path;
        }

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')->with('success', 'Viatura atualizada com sucesso!');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->photo) {
            Storage::disk('public')->delete($vehicle->photo);
        }
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Viatura removida com sucesso!');
    }
}
