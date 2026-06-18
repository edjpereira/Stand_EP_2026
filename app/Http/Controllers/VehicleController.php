<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\SoftDeleteHandler;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    use SoftDeleteHandler;

    public function index(Request $request)
    {
        // Iniciamos a query
        $query = Vehicle::query();


        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('make', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('plate', 'like', "%{$search}%");
            });
        }

        $allowedSorts = ['id', 'make', 'model', 'year', 'price'];
        $sortBy = $request->input('sort_by', 'id'); // Padrão: id
        $sortOrder = $request->input('sort_order', 'asc'); // Padrão: asc

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $vehicles = $query->paginate(25)->withQueryString();;

        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        // 1. Normalizar a matrícula antes da validação
        if ($request->has('plate')) {
            $request->merge(['plate' => $this->formatPortuguesePlate($request->input('plate'))]);
        }

        // 2. Validação Estrita para Portugal
        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'plate' => [
                'required',
                'string',
                'regex:/^([A-Z]{2}-[0-9]{2}-[0-9]{2}|[0-9]{2}-[0-9]{2}-[A-Z]{2}|[0-9]{2}-[A-Z]{2}-[0-9]{2}|[A-Z]{2}-[0-9]{2}-[A-Z]{2})$/',
                'unique:vehicles,plate'
            ],
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'mileage' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:available,sold',
        ], [
            'plate.regex' => 'A matrícula indicada não é uma matrícula portuguesa válida (Formatos aceites: AA-00-00, 00-00-AA, 00-AA-00 ou AA-00-AA).',
            'plate.unique' => 'Esta matrícula já se encontra registada no sistema.'
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('vehicles', 'public');
        }

        Vehicle::create($validated);

        $perPage = 25;
        $lastPage = ceil(Vehicle::count() / $perPage);

        return redirect()->route('vehicles.index', ['page' => $lastPage])
            ->with('success', 'Viatura registada com sucesso!');
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
        // 1. Normalizar a matrícula antes da validação no update
        if ($request->has('plate')) {
            $request->merge(['plate' => $this->formatPortuguesePlate($request->input('plate'))]);
        }

        // 2. Validação Estrita para Portugal no update
        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'plate' => [
                'required',
                'string',
                'regex:/^([A-Z]{2}-[0-9]{2}-[0-9]{2}|[0-9]{2}-[0-9]{2}-[A-Z]{2}|[0-9]{2}-[A-Z]{2}-[0-9]{2}|[A-Z]{2}-[0-9]{2}-[A-Z]{2})$/',
                Rule::unique('vehicles', 'plate')->ignore($vehicle->id)
            ],
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'mileage' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:available,sold',
        ], [
            'plate.regex' => 'A matrícula indicada não é uma matrícula portuguesa válida (Formatos aceites: AA-00-00, 00-00-AA, 00-AA-00 ou AA-00-AA).',
            'plate.unique' => 'Esta matrícula já se encontra registada no sistema.'
        ]);

        if ($request->hasFile('photo')) {
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
        $msg = $this->handleSoftDelete($vehicle, 'viatura');
        return redirect()->route('vehicles.index')->with('success', $msg);
    }

    private function formatPortuguesePlate($plate)
    {
        $cleanPlate = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $plate));

        if (strlen($cleanPlate) === 6) {
            return implode('-', str_split($cleanPlate, 2));
        }
        return $cleanPlate;
    }
    public function reports()
    {
        // Dados estratégicos para o relatório/capturas de ecrã
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('status', 'available')->count();
        $soldVehicles = Vehicle::where('status', 'sold')->count();

        // Valor total do stock disponível em €
        $stockValue = Vehicle::where('status', 'available')->sum('price');

        // Total de faturação (carros vendidos)
        $totalSalesValue = Vehicle::where('status', 'sold')->sum('price');

        // Média de idades do stock (ano atual - ano do carro)
        $currentYear = date('Y');
        $averageAge = Vehicle::where('status', 'available')
            ->selectRaw("AVG(? - year) as avg_age", [$currentYear])
            ->first()->avg_age ?? 0;

        return view('admin.reports', compact(
            'totalVehicles',
            'availableVehicles',
            'soldVehicles',
            'stockValue',
            'totalSalesValue',
            'averageAge'
        ));
    }
}
