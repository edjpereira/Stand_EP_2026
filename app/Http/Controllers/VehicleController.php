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
        $query = Vehicle::query();

        // 1. Filtro de Pesquisa Geral (Texto)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('make', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('plate', 'like', "%{$search}%");
            });
        }

        // 2. Filtro de Quilometragem
        if ($request->filled('km_min') || $request->filled('km_max')) {
            if ($request->filled('km_min') && $request->filled('km_max')) {
                $kmInicial = (int) $request->input('km_min');
                $kmFinal = (int) $request->input('km_max');

                $minKm = min($kmInicial, $kmFinal);
                $maxKm = max($kmInicial, $kmFinal);

                if ($maxKm >= 300000 && ($kmInicial == 300000 || $kmFinal == 300000)) {
                    $query->where('mileage', '>=', $minKm);
                } else {
                    $query->whereBetween('mileage', [$minKm, $maxKm]);
                }
            } elseif ($request->filled('km_min')) {
                $query->where('mileage', '>=', (int) $request->input('km_min'));
            } elseif ($request->filled('km_max')) {
                $maxKm = (int) $request->input('km_max');

                if ($maxKm >= 300000) {
                    $query->where('mileage', '>=', 300000);
                } else {
                    $query->where('mileage', '<=', $maxKm);
                }
            }
        }

        // 3. Filtro de Ano
        if ($request->filled('year_min') || $request->filled('year_max')) {
            if ($request->filled('year_min') && $request->filled('year_max')) {
                $anoInicial = (int) $request->input('year_min');
                $anoFinal = (int) $request->input('year_max');

                $minYear = min($anoInicial, $anoFinal);
                $maxYear = max($anoInicial, $anoFinal);

                $query->whereBetween('year', [$minYear, $maxYear]);
            } elseif ($request->filled('year_min')) {
                $query->where('year', '>=', (int) $request->input('year_min'));
            } elseif ($request->filled('year_max')) {
                $query->where('year', '<=', (int) $request->input('year_max'));
            }
        }

        // 4. Filtro de Combustível
        if ($request->filled('fuel')) {
            $query->where('fuel', $request->fuel);
        }

        // Ordenação e Paginação
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $vehicles = $query->paginate(10)->withQueryString();

        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        if ($request->has('plate')) {
            $request->merge(['plate' => $this->formatPortuguesePlate($request->input('plate'))]);
        }

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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'status' => 'required|in:available,sold',
        ], [
            'plate.regex' => 'A matrícula indicada não é uma matrícula portuguesa válida.',
            'plate.unique' => 'Esta matrícula já se encontra registada no sistema.',
            'photo.max' => 'A fotografia é demasiado grande. Reduza o tamanho para menos de 4 MB.'
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('vehicles', 'public');
            $validated['photo'] = $path;
        }

        Vehicle::create($validated);

        // Ajustado para bater certo com os 10 itens por página definidos no index
        $perPage = 10;
        $lastPage = ceil(Vehicle::count() / $perPage);

        return redirect()->route('vehicles.index', ['page' => $lastPage])
            ->with('success', 'Viatura registada com sucesso!');
    }

    public function show(Vehicle $vehicle)
    {
        $marcaSlug = strtolower(trim($vehicle->make));
        $caminhoFotoMarca = "images/{$marcaSlug}.jpg";
        if (!file_exists(public_path($caminhoFotoMarca))) {
            $caminhoFotoMarca = "images/default_car.jpg";
        }

        $diretorioVeiculo = "vehicles/{$vehicle->id}";
        $galeriaFotos = [];

        if (Storage::disk('public')->exists($diretorioVeiculo)) {
            $ficheiros = Storage::disk('public')->files($diretorioVeiculo);
            sort($ficheiros);

            foreach ($ficheiros as $ficheiro) {
                $galeriaFotos[] = asset('storage/' . $ficheiro);
            }
        }

        if (empty($galeriaFotos)) {
            $galeriaFotos[] = asset($caminhoFotoMarca);
        }

        return view('vehicles.show', compact('vehicle', 'galeriaFotos', 'caminhoFotoMarca'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        if ($request->has('plate')) {
            $request->merge(['plate' => $this->formatPortuguesePlate($request->input('plate'))]);
        }

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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'status' => 'required|in:available,sold',
        ], [
            'plate.regex' => 'A matrícula indicada não é uma matrícula portuguesa válida.',
            'plate.unique' => 'Esta matrícula já se encontra registada no sistema.',
            'photo.max' => 'A fotografia é demasiado grande. Reduza o tamanho para menos de 4 MB.'
        ]);

        if ($request->hasFile('photo')) {
            if ($vehicle->photo && Storage::disk('public')->exists($vehicle->photo)) {
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
        $vehicle->delete();

        $mensagem = auth()->user()->role === 'admin'
            ? 'Viatura movida para a reciclagem.'
            : 'Pedido de eliminação de viatura submetido para aprovação.';

        return redirect()->route('vehicles.index')->with('success', $mensagem);
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
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('status', 'available')->count();
        $soldVehicles = Vehicle::where('status', 'sold')->count();
        $stockValue = Vehicle::where('status', 'available')->sum('price');
        $totalSalesValue = Vehicle::where('status', 'sold')->sum('price');

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

    public function uploadPhoto(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ], [
            'photo.max' => 'A fotografia é demasiado grande. Reduza o tamanho para menos de 4 MB.'
        ]);

        $diretorioVeiculo = "vehicles/{$vehicle->id}";

        $totalFotosAtuais = 0;
        if (Storage::disk('public')->exists($diretorioVeiculo)) {
            $totalFotosAtuais = count(Storage::disk('public')->files($diretorioVeiculo));
        }

        if ($totalFotosAtuais >= 5) {
            return redirect()->back()->with('error', 'Limite atingido! Cada viatura pode ter no máximo 5 fotografias.');
        }

        if ($request->hasFile('photo')) {
            $nomeFicheiro = 'foto_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs($diretorioVeiculo, $nomeFicheiro, 'public');

            if ($totalFotosAtuais === 0) {
                $vehicle->update(['photo' => $path]);
            }
        }

        return redirect()->back()->with('success', 'Fotografia adicionada à galeria com sucesso!');
    }

    public function deletePhoto(Request $request, Vehicle $vehicle)
    {
        $urlFotoParaApagar = $request->input('photo_url');

        if ($urlFotoParaApagar) {
            $caminhoRelativo = str_replace(asset('storage/'), '', $urlFotoParaApagar);

            if (Storage::disk('public')->exists($caminhoRelativo)) {
                Storage::disk('public')->delete($caminhoRelativo);
            }

            $diretorioVeiculo = "vehicles/{$vehicle->id}";
            $restantes = Storage::disk('public')->files($diretorioVeiculo);

            if (!empty($restantes)) {
                sort($restantes);
                $vehicle->update(['photo' => $restantes[0]]);
            } else {
                $vehicle->update(['photo' => null]);
                Storage::disk('public')->deleteDirectory($diretorioVeiculo);
            }

            return redirect()->route('vehicles.show', $vehicle->id)->with('success', 'Fotografia removida da galeria.');
        }

        return redirect()->back()->with('error', 'Não foi possível identificar a fotografia a eliminar.');
    }
}
