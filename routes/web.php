<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PublicVehicleController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ReportController;

Route::get('/', [PublicVehicleController::class, 'index'])->name('home');

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home_dashboard');

// =========================================================================
// 1. ÁREA PROTEGIDA - QUALQUER UTILIZADOR AUTENTICADO (Admin e Employee)
// =========================================================================
Route::middleware(['auth'])->group(function () {

    Route::resource('clients', ClientController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('sales', SaleController::class); // Já inclui o método store automaticamente!

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show'); // Ajustado para o padrão REST
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}/delete-photo', [UserController::class, 'deletePhoto'])->name('users.delete-photo');

    Route::post('/crm/store', [CrmController::class, 'store'])->name('crm.store');

    Route::post('/users/{user}/request-admin', [UserController::class, 'requestAdmin'])->name('users.request_admin');
    Route::post('/users/{user}/dismiss-notification', [UserController::class, 'dismissNotification'])->name('users.dismiss_notification');
    Route::get('/sales/{sale}/invoice', [SaleController::class, 'generateInvoice'])->name('sales.invoice');
});

// =========================================================================
// 2. ÁREA PROTEGIDA - EXCLUSIVA PARA ADMINISTRAÇÃO (Gate: admin-only)
// =========================================================================
Route::middleware(['auth', 'can:admin-only'])->prefix('admin')->group(function () {

    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/trash', [AdminController::class, 'unifiedTrash'])->name('admin.trash');

    Route::patch('/clients/{id}/restore', [AdminController::class, 'restoreClient'])->name('clients.restore');
    Route::delete('/clients/{id}/force', [AdminController::class, 'forceDeleteClient'])->name('clients.force');

    Route::delete('/admin/vehicles/{vehicle}/force', [AdminController::class, 'forceDeleteVehicle'])->name('vehicles.force')->withTrashed();
    Route::post('/admin/vehicles/{vehicle}/restore', [AdminController::class, 'restoreVehicle'])->name('vehicles.restore')->withTrashed();

    Route::post('/vehicles/{vehicle}/upload-photo', [VehicleController::class, 'uploadPhoto'])->name('vehicles.upload_photo');
    Route::delete('/vehicles/{vehicle}/delete-photo', [VehicleController::class, 'deletePhoto'])->name('vehicles.delete_photo');

    Route::delete('/crm/interaction/{id}', [CrmController::class, 'destroyInteraction'])->name('crm.destroy-interaction');

    Route::get('/audit', [AuditController::class, 'index'])->name('admin.audit');

    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');

    Route::post('/admin/users/{user}/handle-request/{action}', [UserController::class, 'handleAdminRequest'])
        ->name('admin.users.handle_request')
        ->middleware('auth');
});
