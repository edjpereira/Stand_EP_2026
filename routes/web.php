<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\SaleController;

// Rota inicial
Route::get('/', function () {
    return view('welcome');
});

// Rotas de Autenticação
Auth::routes();

// Rota do Dashboard
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Área para utilizadores autenticados
Route::middleware(['auth'])->group(function () {

    // Rotas do tipo Resource geram automaticamente todas as URLs para o CRUD
    Route::resource('clients', ClientController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('sales', SaleController::class);

});
