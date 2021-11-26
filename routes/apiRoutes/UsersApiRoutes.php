<?php

use App\Http\Controllers\Api\UsersController;
use Illuminate\Support\Facades\Route;
// $middle=['jwt.verify.role:admin|office|finance|approves'];
$middle=[];
Route::group(['middleware' => $middle], function() {//
    Route::get('/', [UsersController::class,'index'])->name('api.users.index');
    Route::get('/{id}', [UsersController::class,'show'])->name('api.users.show');
    Route::post('/', [UsersController::class,'store'])->name('api.users.store');
    Route::put('/{id}', [UsersController::class,'update'])->name('api.users.update');
    Route::delete('/{id}', [UsersController::class,'destroy'])->name('api.users.destroy');
});
