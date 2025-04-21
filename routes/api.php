<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('brands')->group(function(){
    // list brands by countries
    Route::get('/', [BrandController::class, 'index']);
    // create Brand
    Route::post('/create', [BrandController::class, 'createBrand']);
    // get brand data
    Route::get('/{id}', [BrandController::class, 'getBrandDetails']);
    // edit brand
    Route::put('/{id}', [BrandController::class, 'editBrand']);
    // delete a brand
    Route::delete('/{id}', [BrandController::class, 'deleteBrand']);
    // route to assign a country to a brand
    Route::post('/{brandId}/assign-country', [BrandController::class, 'assignCountry']);
});
