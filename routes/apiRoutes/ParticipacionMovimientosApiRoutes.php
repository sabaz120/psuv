<?php

use App\Http\Controllers\Api\ParticipacionMovimientoController as Controller;
use Illuminate\Support\Facades\Route;
Route::get('/', [Controller::class,'index'])->name('api.participacion.movimiento.index');
Route::post('/', [Controller::class,'store'])->name('api.participacion.movimiento.store');
Route::put('/{id}', [Controller::class,'update'])->name('api.participacion.movimiento.update');
Route::delete('/{id}', [Controller::class,'delete'])->name('api.participacion.movimiento.destroy');
Route::prefix('familiares')->group(function () {
    Route::get('/', [Controller::class,'indexFamily'])->name('api.participacion.movimiento.family.index');
    Route::post('/', [Controller::class,'storeFamily'])->name('api.participacion.movimiento.family.store');
    Route::put('/{id}', [Controller::class,'updateFamily'])->name('api.participacion.movimiento.family.update');
    Route::delete('/{id}', [Controller::class,'deleteFamily'])->name('api.participacion.movimiento.family.destroy');
});

