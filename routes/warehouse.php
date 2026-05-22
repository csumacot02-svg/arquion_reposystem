<?php

use App\Http\Controllers\Warehouse\ItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Warehouse Inventory Management Routes
|--------------------------------------------------------------------------
|
| Add these routes to your routes/web.php file.
| This registers all 7 CRUD routes for the ItemController:
|
|  GET    /warehouse/items           → index()   (list all)
|  GET    /warehouse/items/create    → create()  (show add form)
|  POST   /warehouse/items           → store()   (save new item)
|  GET    /warehouse/items/{item}    → show()    (view single item)
|  GET    /warehouse/items/{item}/edit → edit()  (show edit form)
|  PUT    /warehouse/items/{item}    → update()  (save edits)
|  DELETE /warehouse/items/{item}    → destroy() (delete item)
|
*/

Route::prefix('warehouse')
     ->name('warehouse.')
     ->group(function () {

         // Redirect /warehouse → /warehouse/items
         Route::get('/', fn() => redirect()->route('warehouse.items.index'));

         // Full resource CRUD for Items
         Route::resource('items', ItemController::class);
     });
