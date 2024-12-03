<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecapController;
use Illuminate\Support\Facades\Route;

Route::prefix("customers")->group(function () {
    Route::post("/", [CustomerController::class, "create"]);
    Route::get("/", [CustomerController::class, "findMany"]);
    Route::put("/{id}", [CustomerController::class, "update"]);
    Route::get("/{id}", [CustomerController::class, "find"]);
    Route::delete("/{id}", [CustomerController::class, "delete"]);
});

Route::prefix("products")->group(function () {
    Route::post("/", [ProductController::class, "create"]);
    Route::get("/", [ProductController::class, "findMany"]);
    Route::put("/{id}", [ProductController::class, "update"]);
    Route::get("/{id}", [ProductController::class, "find"]);
    Route::delete("/{id}", [ProductController::class, "delete"]);
});

Route::prefix("orders")->group(function () {
    Route::post("/", [OrderController::class, "create"]);
    Route::get("/", [OrderController::class, "findMany"]);
    Route::put("/{id}", [OrderController::class, "update"]);
    Route::get("/{id}", [OrderController::class, "find"]);
    Route::delete("/{id}", [OrderController::class, "delete"]);
});

Route::prefix("order-products")->group(function () {
    Route::put("/{id}", [OrderProductController::class, "update"]);
    Route::delete("/{sale_id}/{item_code}", [OrderProductController::class, "delete"]);
});

Route::prefix("recap")->group(function () {
    Route::get("/total-price", [RecapController::class, "totalPrice"]);
});
