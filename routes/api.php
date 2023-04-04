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

Route::get('videoDatas', [VideoDataController::class, 'index']);
Route::get('videoDatas/list/', [VideoDataController::class, 'getVideoList']);
Route::get('videoDatas/byVideoId/{id}', [VideoDataController::class, 'showByVideoId']);
Route::post('videoDatas/checkAnswers/{id}', [VideoDataController::class, 'checkAnswers']);
Route::middleware('auth:sanctum')->post('videoDatas', [VideoDataController::class, 'store']);
// Route::put('videoDatas/{id}', [VideoDataController::class, 'update']);
Route::delete('videoDatas/{id}', [VideoDataController::class, 'delete']);