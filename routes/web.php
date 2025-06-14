<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::prefix('customers')->group(function () {
  Route::get('/', [CustomerController::class, 'index']);
  Route::get('/{id}', [CustomerController::class, 'show']);
});


Route::fallback(function () {
  return response()->json([
    'message' => 'Route not found. Please check the URL or method.',
  ], 404);
});
