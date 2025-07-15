<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MensualidadController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ReciboController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\DashboardController::class, 'index'])->name('home')->middleware('auth');
Route::get('/admin', [App\Http\Controllers\DashboardController::class, 'index'])->name('admin')->middleware('auth');



// rutas clientes
Route::get('/admin/clientes/data', [ClienteController::class, 'data'])->name('admin.clientes.data')->middleware('auth');
Route::get('/admin/clientes/dataMora', [ClienteController::class, 'dataMora'])->name('admin.clientes.dataMora');
Route::get('/admin/clientes/dataCorte', [ClienteController::class, 'dataCorte'])->name('admin.clientes.dataCorte');
Route::get('/admin/clientes', [ClienteController::class, 'index'])->name('admin.clientes.index')->middleware('auth');
Route::get('/admin/clientes/mora', [ClienteController::class, 'mora'])->name('admin.clientes.clientesmora');
Route::get('/admin/clientes/list', [ClienteController::class, 'list'])->name('admin.clientes.list')->middleware('auth');
Route::get('/admin/clientes/{id}', [ClienteController::class, 'show'])->name('admin.clientes.show')->middleware('auth');
Route::post('/admin/clientes', [ClienteController::class, 'store'])->name('admin.clientes.store')->middleware('auth');
Route::put('/admin/clientes/{id}', [ClienteController::class, 'update'])->name('admin.clientes.update')->middleware('auth');
Route::delete('/admin/clientes/{id}', [ClienteController::class, 'destroy'])->name('admin.clientes.destroy')->middleware('auth');


Route::get('admin/clientes/{cliente}/historial', [PagoController::class, 'historial'])->name('admin.clientes.historial');
Route::get('admin/clientes/{cliente}/pagar', [PagoController::class, 'pagar'])->name('admin.clientes.pagar');
Route::post('admin/pagos/store', [PagoController::class, 'store'])->name('admin.pagos.store');
Route::get('admin/pagos/guardar-pdf/{id}', [App\Http\Controllers\PagoController::class, 'pdf'])->name('admin.pagos.pdf');
Route::post('admin/pagos/recibo', [App\Http\Controllers\PagoController::class, 'store'])->name('admin.pagos.recibo');


// rutas empleados 
Route::get('/admin/empleados', [EmpleadoController::class, 'index'])->name('admin.empleados.index')->middleware('auth');
Route::get('/admin/empleados/data', [EmpleadoController::class, 'data'])->name('admin.empleados.data')->middleware('auth');
Route::post('/admin/empleados', [EmpleadoController::class, 'store'])->name('admin.empleados.store')->middleware('auth');
Route::get('/admin/empleados/{id}', [EmpleadoController::class, 'show'])->name('admin.empleados.show')->middleware('auth');
Route::put('/admin/empleados/{id}', [EmpleadoController::class, 'update'])->name('admin.empleados.update')->middleware('auth');
Route::delete('/admin/empleados/{id}', [EmpleadoController::class, 'destroy'])->name('admin.empleados.destroy')->middleware('auth');






// Route::get('/admin/clientes/{id}/historial', [ClienteController::class, 'historial'])->name('admin.clientes.historial')->middleware('auth');

// rutas planes
Route::resource('admin/planes', PlanController::class)->names('planes')->middleware('auth');


Route::get('/admin/clientes/{id}/pagar', [MensualidadController::class, 'formularioPago'])->name('admin.mensualidades.formulario');
Route::post('/admin/clientes/{id}/pagar', [MensualidadController::class, 'realizarPago'])->name('admin.mensualidades.pagar');
// Route::get('/admin/recibos/{id}', [ReciboController::class, 'ver'])->name('admin.recibos.ver');
// Route::get('/admin/recibos/{id}/pdf', [ReciboController::class, 'generarPDF'])->name('admin.recibos.pdf');
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

// Vistas para los detalles de cada card:
Route::get('/admin/clientes/en-mora', [ClienteController::class, 'enMora'])->name('admin.clientes.mora');
Route::get('/admin/clientes/proximo-corte', [ClienteController::class, 'proxCorte'])->name('admin.clientes.prox_corte');
Route::get('/admin/pagos/hoy', [PagoController::class, 'hoy'])->name('admin.pagos.hoy');


