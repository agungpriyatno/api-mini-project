<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RecapController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleItemController;
use Illuminate\Support\Facades\Route;

Route::prefix("customers")->group(function () {
    Route::post("/", [CustomerController::class, "create"]);
    Route::get("/", [CustomerController::class, "findMany"]);
    Route::put("/{id}", [CustomerController::class, "update"]);
    Route::get("/{id}", [CustomerController::class, "find"]);
    Route::delete("/{id}", [CustomerController::class, "delete"]);
});

Route::prefix("items")->group(function () {
    Route::post("/", [ItemController::class, "create"]);
    Route::get("/", [ItemController::class, "findMany"]);
    Route::put("/{id}", [ItemController::class, "update"]);
    Route::get("/{id}", [ItemController::class, "find"]);
    Route::delete("/{id}", [ItemController::class, "delete"]);
});

Route::prefix("sales")->group(function () {
    Route::post("/", [SaleController::class, "create"]);
    Route::get("/", [SaleController::class, "findMany"]);
    Route::put("/{id}", [SaleController::class, "update"]);
    Route::get("/{id}", [SaleController::class, "find"]);
    Route::delete("/{id}", [SaleController::class, "delete"]);
});

Route::prefix("sale-items")->group(function () {
    Route::put("/{id}", [SaleItemController::class, "update"]);
    Route::delete("/{sale_id}/{item_code}", [SaleItemController::class, "delete"]);
});

Route::prefix("recap")->group(function () {
    Route::get("/total-price", [RecapController::class, "totalPrice"]);
});
