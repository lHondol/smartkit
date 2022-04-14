<?php

use App\Http\Controllers\BundleController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('product/add', [ProductController::class, 'add']);
Route::patch('product/update/{id}', [ProductController::class, 'update']);
Route::delete('product/delete/{id}', [ProductController::class, 'delete']);
Route::get('product/all', [ProductController::class, 'all']);
Route::get('product/get/{id}', [ProductController::class, 'get']);

Route::post('bundle/add', [BundleController::class, 'add']);
Route::patch('bundle/update/{id}', [BundleController::class, 'update']);
Route::delete('bundle/delete/{id}', [BundleController::class, 'delete']);
Route::get('bundle/all', [BundleController::class, 'all']);
Route::get('bundle/get/{id}', [BundleController::class, 'get']);
