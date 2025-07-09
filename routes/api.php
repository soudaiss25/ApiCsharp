<?php

use App\Http\Controllers\Api\ContratLocationController;
use App\Http\Controllers\Api\PaiementController;
use App\Http\Controllers\Api\ProprietaireController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\Api\AppartementController;

Route::apiResource('appartements', AppartementController::class);


Route::apiResource('users', UserController::class);
Route::apiResource('proprietaires', ProprietaireController::class);
Route::apiResource('contrat-locations', ContratLocationController::class);
Route::apiResource('paiements', PaiementController::class);

use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/setup-admin', [UserController::class, 'setupAdmin']);
Route::put('/users/{id}/update-password', [UserController::class, 'updatePassword']);

