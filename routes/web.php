<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MensualidadController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ReciboController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\AdminController::class, 'index'])->name('home')->middleware('auth');
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin')->middleware('auth');



// rutas mensualidad
Route::get('/admin/clientes/data', [ClienteController::class, 'data'])->name('admin.clientes.data');
Route::get('/admin/clientes/list', [ClienteController::class, 'list'])->name('admin.clientes.list')->middleware('auth');
Route::resource('admin/clientes',ClienteController::class)->names('admin.clientes')->middleware('auth');
Route::get('/admin/clientes/{id}/historial', [ClienteController::class, 'historial'])->name('admin.clientes.historial')->middleware('auth');

// rutas planes
Route::resource('admin/planes', PlanController::class)->names('planes')->middleware('auth');


Route::get('/admin/clientes/{id}/pagar', [MensualidadController::class, 'formularioPago'])->name('admin.mensualidades.formulario');
Route::post('/admin/clientes/{id}/pagar', [MensualidadController::class, 'realizarPago'])->name('admin.mensualidades.pagar');
Route::get('/admin/recibos/{id}', [ReciboController::class, 'ver'])->name('admin.recibos.ver');
Route::get('/admin/recibos/{id}/pdf', [ReciboController::class, 'generarPDF'])->name('admin.recibos.pdf');
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
