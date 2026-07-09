<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $volMes = \App\Models\Sale::whereMonth('created_at', now()->month)->sum('sale_amount');
        $volAno = \App\Models\Sale::whereYear('created_at', now()->year)->sum('sale_amount');

        $totalClientes = \App\Models\Client::count();
        $clientes24h = \App\Models\Client::where('created_at', '>=', now()->subDay())->count();

        $vendasTrintaDias = [];
        for ($i = 29; $i >= 0; $i--) {
            $data = now()->subDays($i)->format('Y-m-d');
            $vendasTrintaDias[] = \App\Models\Sale::whereDate('created_at', $data)->count();
        }

        $availableVehicles = \App\Models\Vehicle::where('status', 'available')->get();
        $clients = \App\Models\Client::all();

        return view('home', compact(
            'volMes',
            'volAno',
            'totalClientes',
            'clientes24h',
            'vendasTrintaDias',
            'availableVehicles',
            'clients'
        ));
    }
}
