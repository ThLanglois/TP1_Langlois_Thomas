<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;

Route::get('/equipment', [EquipmentController::class, 'index']);
Route::get('/equipment/{id}', [EquipmentController::class, 'show']);
Route::get('/equipment/{id}/popularity', [EquipmentController::class, 'popularity']);
Route::get('/equipment/{id}/average-rental-price', [EquipmentController::class, 'averageRentalPrice']);

Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);

Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);