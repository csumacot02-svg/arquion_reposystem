<?php

use App\Http\Controllers\RequestFormController;
use App\Http\Controllers\Warehouse\ItemController;
use App\Http\Controllers\Warehouse\RequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('request')->group(function () {
    Route::get('/', [RequestFormController::class, 'create'])->name('request-form.create');
    Route::post('/', [RequestFormController::class, 'store'])->name('request-form.store');
    Route::get('/success', [RequestFormController::class, 'success'])->name('request-form.success');
});

Route::prefix('warehouse')->name('warehouse.')->group(function () {
    Route::get('/', fn () => redirect()->route('warehouse.items.index'));

    Route::get('/items/{item}/image', [ItemController::class, 'image'])->name('items.image');
    Route::resource('items', ItemController::class);

    Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
    Route::post('/requests/{inventoryRequest}/approve', [RequestController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{inventoryRequest}/reject', [RequestController::class, 'reject'])->name('requests.reject');
    Route::delete('/requests/{inventoryRequest}', [RequestController::class, 'destroy'])->name('requests.destroy');
});
