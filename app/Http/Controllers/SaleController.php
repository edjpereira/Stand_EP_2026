<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct()
    {
        // O middleware 'auth' garante o login; o 'admin' garante o cargo sénior
        $this->middleware('auth');
        $this->middleware('admin')->only(['edit', 'update', 'destroy']);
    }
    public function index()
    {
        $sales = Sale::with(['client', 'vehicle'])->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $clients = Client::all();

        $vehicles = Vehicle::where('status', 'available')->get();

        return view('sales.create', compact('clients', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'sale_date' => 'required|date',
            'sale_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Verificar se a viatura não foi já vendida
        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        if ($vehicle->status === 'sold') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['vehicle_id' => 'Esta viatura já foi vendida e não está disponível.']);
        }

        DB::transaction(function () use ($validated, $vehicle) {
            Sale::create($validated);

            // Actualizar estado da viatura
            $vehicle->update(['status' => 'sold']);
        });

        return redirect()->route('sales.index')->with('success', 'Venda registada com sucesso e viatura marcada como vendida!');
    }

    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    // Nota: Geralmente, vendas não se editam por questões fiscais ou de auditoria,
    // mas como o enunciado pede CRUD completo, inclui-se    o edit/update de notas ou valores.
    public function edit(Sale $sale)
    {
        $clients = Client::all();
        $vehicles = Vehicle::all(); // No edit permitimos ver todas para o caso de manter a mesma
        return view('sales.edit', compact('sale', 'clients', 'vehicles'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'sale_date' => 'required|date',
            'sale_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $sale->update($validated);

        return redirect()->route('sales.index')->with('success', 'Dados da venda atualizados com sucesso!');
    }

    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            // Se apagarmos o registo da venda, a viatura volta a ficar disponível
            $sale->vehicle->update(['status' => 'available']);
            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Venda cancelada e viatura voltou ao stock disponível!');
    }
}
