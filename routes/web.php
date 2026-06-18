<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

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
// Rotas para edição de perfis (reservadas a admins)
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil/atualizar', [ProfileController::class, 'update'])->name('profile.update');
});

// Rotas exclusivas do Admin para gestão de eliminação
Route::middleware(['auth', 'can:admin-only'])->prefix('admin')->group(function () {

    // Página centralizada de pedidos
    Route::get('/trash', [AdminController::class, 'unifiedTrash'])->name('admin.trash');

    // Clientes
    Route::patch('/clients/{id}/restore', [AdminController::class, 'restoreClient'])->name('clients.restore');
    Route::delete('/clients/{id}/force', [AdminController::class, 'forceDeleteClient'])->name('clients.force');

    // Veículos
    Route::patch('/vehicles/{id}/restore', [AdminController::class, 'restoreVehicle'])->name('vehicles.restore');
    Route::delete('/vehicles/{id}/force', [AdminController::class, 'forceDeleteVehicle'])->name('vehicles.force');
});

// Rota Resource normal para o CRUD
Route::middleware(['auth'])->group(function () {
    Route::resource('clients', ClientController::class);
});

// Form CRM
Route::post('/crm/store', [App\Http\Controllers\CrmController::class, 'store'])->name('crm.store');

// Rotas para a gestão de utilizadores pelo admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/admin/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::get('/admin/audit', [AuditController::class, 'index'])->name('admin.audit');
    Route::get('/admin/reports', [VehicleController::class, 'reports'])->name('admin.reports');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // ... tuas outras rotas
    Route::get('/admin/audit', [AuditController::class, 'index'])->name('admin.audit');
});
