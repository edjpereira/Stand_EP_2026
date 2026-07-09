<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Sale;
use App\Models\Interaction;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $totalSalesValue = Sale::sum('sale_amount');
        $soldVehicles = Vehicle::where('status', 'sold')->count();
        $availableVehicles = Vehicle::where('status', 'available')->count();
        $totalVehicles = Vehicle::count();

        $stockValue = Vehicle::where('status', 'available')->sum('price');
        $averageAge = Vehicle::where('status', 'available')->avg(DB::raw('YEAR(NOW()) - year'));

        $startDate = $request->input('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $salesPerPeriod = Sale::with(['vehicle', 'client'])
            ->whereBetween('sale_date', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ])
            ->orderBy('sale_date', 'desc')
            ->get();

        // 3. Modelos Mais Vendidos
        $topVehicles = Vehicle::select('make', 'model', DB::raw('count(*) as qty'), DB::raw('sum(price) as total_value'))
            ->where('status', 'sold')
            ->groupBy('make', 'model')
            ->orderBy('qty', 'desc')
            ->take(5)
            ->get();

        $totalCrmActions = Interaction::count();
        $crmConversionRate = $totalCrmActions > 0 ? round(($soldVehicles / $totalCrmActions) * 100, 1) : 0;

        $avgStockDays = DB::table('vehicles')
            ->join('sales', 'vehicles.id', '=', 'sales.vehicle_id')
            ->select(DB::raw('DATEDIFF(sales.sale_date, vehicles.created_at) as days'))
            ->get()
            ->avg('days');
        $avgStockDays = round($avgStockDays ?? 0);

        $totalInteractions = Interaction::count();
        $leadsChannels = Interaction::select('type as name', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get()
            ->map(function ($channel) use ($totalInteractions) {
                // Traduz o tipo para um nome legível
                $translations = ['phone' => '📞 Telefone', 'email' => '✉️ Email', 'visit' => '🤝 Presencial', 'site' => '💻 Digital'];
                $channel->name = $translations[$channel->name] ?? ucfirst($channel->name);
                $channel->percentage = $totalInteractions > 0 ? round(($channel->count / $totalInteractions) * 100, 1) : 0;
                return $channel;
            });

        return view('admin.reports', compact(
            'totalSalesValue',
            'soldVehicles',
            'availableVehicles',
            'totalVehicles',
            'stockValue',
            'averageAge',
            'salesPerPeriod',
            'topVehicles',
            'totalCrmActions',
            'crmConversionRate',
            'avgStockDays',
            'leadsChannels'
        ));
    }
}
