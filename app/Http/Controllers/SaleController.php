<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->only(['edit', 'update', 'destroy']);
    }

    public function index()
    {
        $sales = Sale::with(['client', 'vehicle', 'seller'])->get(); // Incluído o 'seller' para otimizar as queries
        return view('sales.index', compact('sales'));
    }

    public function create(Request $request)
    {
        $clients = Client::all();
        $vehicles = Vehicle::where('status', 'available')->get();

        $selectedVehicleId = null;

        if ($request->has('vehicle_id')) {
            $vehicle = Vehicle::find($request->query('vehicle_id'));

            if ($vehicle && $vehicle->status !== 'sold') {
                $selectedVehicleId = $vehicle->id;
            }
        }

        return view('sales.create', compact('clients', 'vehicles', 'selectedVehicleId'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'client') {
            abort(403, 'Não tens permissão para registar vendas.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'sale_date' => 'required|date',
            'sale_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        if ($vehicle->status === 'sold') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['vehicle_id' => 'Esta viatura já foi vendida e não está disponível.']);
        }

        $validated['seller_id'] = auth()->id();

        $sale = null;

        DB::transaction(function () use ($validated, &$sale) {
            $sale = Sale::create($validated);

            $sale->vehicle->update([
                'status' => 'sold'
            ]);
        });

        return redirect()->route('sales.show', $sale->id)
            ->with('success', 'Venda registada com sucesso e viatura retirada de stock!');
    }

    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $clients = Client::all();
        $vehicles = Vehicle::all();
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
        $detalhes = "Venda nº {$sale->id} - Cliente: {$sale->client->name} - Viatura: {$sale->vehicle->make} {$sale->vehicle->model} (Matrícula: {$sale->vehicle->plate}) - Valor de Fecho: " . number_format($sale->sale_amount, 2, ',', '.') . "€";

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Eliminação Definitiva',
            'model_type' => 'Venda',
            'model_id' => $sale->id,
            'details' => $detalhes
        ]);

        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Venda eliminada e registada no histórico de auditoria.');
    }

    public function generateInvoice(Sale $sale)
    {
        $sale->load(['client', 'vehicle']);

        $pdf = Pdf::loadView('sales.invoice', compact('sale'));

        return $pdf->stream("fatura_venda_{$sale->id}.pdf");
    }
}
