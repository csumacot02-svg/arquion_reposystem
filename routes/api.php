<?php

use App\Http\Controllers\Warehouse\RequestController;
use Illuminate\Support\Facades\Route;

Route::get('/available-items', [RequestController::class, 'availableItems']);
Route::post('/inventory-requests', [RequestController::class, 'receiveFromForm']);