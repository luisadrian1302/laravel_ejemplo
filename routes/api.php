<?php

// use App\Http\Controllers\CategoriasController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\GastosController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuariosControler;
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

// Route::middleware('auth:api')->get('/categories', function (Request $request) {
//     return $request->user();
// });


Route::post("login", [LoginController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->get('/getUser/{id}', [AuthController::class, 'getUserApi']);
Route::middleware('auth:sanctum')->put('/getUser/{id}', [AuthController::class, 'updateAPI']);
Route::middleware('auth:sanctum')->delete('/getUser/{id}', [AuthController::class, 'deleteAPI']);


Route::middleware('auth:sanctum')->get('/categories', [CategoriasController::class, 'indexAPI']);
Route::middleware('auth:sanctum')->post('/categories', [CategoriasController::class, 'storeAPI']);
Route::middleware('auth:sanctum')->get('/categories/{id}', action: [CategoriasController::class, 'getAPI']);
Route::middleware('auth:sanctum')->delete('/categories/{id}', [CategoriasController::class, 'deleteAPI']);
Route::middleware('auth:sanctum')->put('/categories/{id}', [CategoriasController::class, 'updateAPI']);


Route::middleware('auth:sanctum')->post('/gastos/create', [GastosController::class, 'storeAPI']);
Route::middleware('auth:sanctum')->put('/gastos/{id}', [GastosController::class, 'updateAPI']);
Route::middleware('auth:sanctum')->get('/gastos/getall', [GastosController::class, 'indexAPI']);
Route::middleware('auth:sanctum')->get('/gastos/get/{id}', [GastosController::class, 'getAPI']);
Route::middleware('auth:sanctum')->delete('/gastos/{id}', [GastosController::class, 'deleteAPI']);


Route::middleware('auth:sanctum')->get('/verifyAdmin', [AdminController::class, 'index']);
Route::middleware('auth:sanctum')->get('/getUsers', [UsuariosControler::class, 'indexAPI']);


// cambios
Route::middleware('auth:sanctum')->get('/rol', [RolController::class, 'indexAPI']);
Route::middleware('auth:sanctum')->post('/rol', [RolController::class, 'storeAPI']);
Route::middleware('auth:sanctum')->get('/rol/{id}', action: [RolController::class, 'getAPI']);
Route::middleware('auth:sanctum')->delete('/rol/{id}', [RolController::class, 'deleteAPI']);
Route::middleware('auth:sanctum')->put('/rol/{id}', [RolController::class, 'updateAPI']);


