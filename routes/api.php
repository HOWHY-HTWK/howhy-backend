<?php

use App\Http\Controllers\EmailController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|-------------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
*/

//general
Route::get('check', [UserController::class, 'check'])
    ->middleware(['auth:sanctum', 'isCreator', 'verified']);

//videoController
Route::get('videos', [VideoController::class, 'index']);
Route::get('video/{videoId}', [VideoController::class, 'getById']);
Route::get('timecodes/{videoId}', [VideoController::class, 'timecodes']);

Route::get('questions/{videoId}', [VideoController::class, 'questions'])
    ->middleware(['auth:sanctum', 'isCreator', 'verified']);

//questionController
Route::get('question/{id}', [QuestionController::class, 'getById']);

Route::post('question/checkAnswers/', [QuestionController::class, 'checkAnswers']);

Route::post('question', [QuestionController::class, 'storeQuestion'])
    ->middleware(['auth:sanctum', 'isCreator', 'verified']);

Route::post('deleteQuestion/{id}', [QuestionController::class, 'deleteQuestion'])
    ->middleware(['auth:sanctum', 'isCreator', 'verified']);

Route::get('score/', [QuestionController::class, 'score']);

//userController

Route::get('user', [UserController::class, 'index'])
    ->middleware('auth:sanctum');

//admin

Route::get('allowed-email', [EmailController::class, 'getAllowedEmail'])
    ->middleware(['auth:sanctum', 'isAdmin', 'verified']);

Route::post('allowed-email', [EmailController::class, 'setAllowedEmail'])
    ->middleware(['auth:sanctum', 'isAdmin', 'verified']);

Route::delete('allowed-email/{id}', [EmailController::class, 'deleteAllowedEmail'])
    ->middleware(['auth:sanctum', 'isAdmin', 'verified']);

// rights

Route::get('users', [UserController::class, 'getAll'])
    ->middleware(['auth:sanctum', 'isAdmin', 'verified']);

Route::post('makeEditor/{id}', [UserController::class, 'makeEditor'])
    ->middleware(['auth:sanctum', 'isAdmin', 'verified']);

//scripts to change database

// Route::get('transfer', [VideoController::class, 'transferScript']);
