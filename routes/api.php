<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Added routes for API endpoint ( with Middleware of API_TOKEN )
Route::middleware( 'via-token')->controller(HelloController::class)->group(function () {
    Route::get('/hellos', 'index');
    Route::post('/hello/create', 'create');
    Route::post('/hello/update', 'update');
    Route::get('/hello/delete', 'delete');
});
