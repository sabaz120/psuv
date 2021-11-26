<?php

use App\Http\Controllers\Api\ParticipacionInstitucionController as Controller;
use Illuminate\Support\Facades\Route;
Route::get('/', [Controller::class,'index'])->name('api.participacion.institucion.index');
Route::post('/', [Controller::class,'store'])->name('api.participacion.institucion.store');
Route::put('/{id}', [Controller::class,'update'])->name('api.participacion.institucion.update');
Route::delete('/{id}', [Controller::class,'delete'])->name('api.participacion.institucion.destroy');
Route::prefix('familiares')->group(function () {
    Route::get('/', [Controller::class,'indexFamily'])->name('api.participacion.institucion.family.index');
    Route::post('/', [Controller::class,'storeFamily'])->name('api.participacion.institucion.family.store');
    Route::put('/{id}', [Controller::class,'updateFamily'])->name('api.participacion.institucion.family.update');
    Route::delete('/{id}', [Controller::class,'deleteFamily'])->name('api.participacion.institucion.family.destroy');
});

