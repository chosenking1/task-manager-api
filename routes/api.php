<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\TaskController;

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


Route::post('/login', [AuthController::class, 'login']);
Route::Delete('/deleteUser/{$id}', [AuthController::class, 'delete']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/department/index', [DepartmentController::class, 'index'])->withoutMiddleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum']], function(){

Route::resource('/task', TaskController::class);
Route::resource('/department', DepartmentController::class);

Route::post('/logout', [AuthController::class, 'logout']);

});