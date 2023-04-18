<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\VideoData;
use App\Http\Controllers\VideoDataController;

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

//public
Route::get('videoDatas', [VideoDataController::class, 'index']);
Route::get('videoDatas/byVideoId/{id}', [VideoDataController::class, 'showByVideoId']);
Route::post('videoDatas/checkAnswers/{id}', [VideoDataController::class, 'checkAnswers']);
//TODO new method that filters list
Route::get('videoDatas/list/', [VideoDataController::class, 'getVideoList']);


//editor
Route::middleware('auth:sanctum')->middleware('isCreator')->get('check', [VideoDataController::class, 'check']);
// Route::middleware('auth:sanctum')->get('videoDatas/list/', [VideoDataController::class, 'getVideoList']);
Route::middleware('auth:sanctum')->get('videoDatas/all/', [VideoDataController::class, 'getVideoList']);
Route::middleware('auth:sanctum')->post('videoDatas', [VideoDataController::class, 'store']);

//admin
// Route::middleware('auth:sanctum')->get('allowed_email', [VideoDataController::class, 'getAllowedEmail']);
Route::middleware('auth:sanctum')->get('allowed-email', [VideoDataController::class, 'getAllowedEmail']);
Route::middleware('auth:sanctum')->post('allowed-email', [VideoDataController::class, 'setAllowedEmail']);
Route::middleware('auth:sanctum')->delete('allowed-email/{id}', [VideoDataController::class, 'deleteAllowedEmail']);

// Route::put('videoDatas/{id}', [VideoDataController::class, 'update']);
Route::middleware('auth:sanctum')->delete('videoDatas/{id}', [VideoDataController::class, 'delete']);