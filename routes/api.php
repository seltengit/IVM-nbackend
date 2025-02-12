<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OrderController;




/*
|----------------------------------------------------------------------
| API Routes
|----------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider within a group 
| which is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Default route for authenticated user (Sanctum)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

Route::get('/sub-categories', [SubCategoryController::class, 'index']);
Route::get('/sub-categories/{id}', [SubCategoryController::class, 'show']);
Route::post('/sub-categories', [SubCategoryController::class, 'store']);
Route::put('/sub-categories/{id}', [SubCategoryController::class, 'update']);
Route::delete('/sub-categories/{id}', [SubCategoryController::class, 'destroy']);

Route::get('/deliveries', [DeliveryController::class, 'index']);
Route::get('/deliveries/{id}', [DeliveryController::class, 'show']);
Route::post('/deliveries', [DeliveryController::class, 'store']);
Route::put('/deliveries/{id}', [DeliveryController::class, 'update']);
Route::delete('/deliveries/{id}', [DeliveryController::class, 'destroy']);


Route::get('/reports', [ReportController::class, 'index']);
Route::get('/reports/{id}', [ReportController::class, 'show']);
Route::post('/reports', [ReportController::class, 'store']);
Route::put('/reports/{id}', [ReportController::class, 'update']);
Route::delete('/reports/{id}', [ReportController::class, 'destroy']);


Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'store']);
Route::put('/orders/{id}', [OrderController::class, 'update']);
Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

use App\Http\Controllers\StockController;

Route::apiResource('stocks', StockController::class);
