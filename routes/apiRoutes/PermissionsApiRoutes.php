<?php

use App\Http\Controllers\Api\PermissionController as Controller;
use Illuminate\Support\Facades\Route;
$middle=[];
Route::group(['middleware' => $middle], function() {//
    Route::get('/', [Controller::class,'index'])->name('api.permissions.index');
});
