<?php

use App\Http\Controllers\Api\RoleController as Controller;
use Illuminate\Support\Facades\Route;
$middle=[];
Route::group(['middleware' => $middle], function() {//
    Route::get('/', [Controller::class,'index'])->name('api.roles.index');
    Route::get('/{id}', [Controller::class,'show'])->name('api.roles.show');
    Route::post('/', [Controller::class,'store'])->name('api.roles.store');
    Route::put('/{id}', [Controller::class,'update'])->name('api.roles.update');
    Route::delete('/{id}', [Controller::class,'destroy'])->name('api.roles.destroy');
});
