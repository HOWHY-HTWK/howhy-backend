<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|-------------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
*/

//general
Route::middleware('auth:sanctum')
    ->middleware('isCreator')
    ->middleware('verified')
    ->get('check', [VideoDataController::class, 'check']);

//videoController
Route::get('videos', [VideoController::class, 'index']);
Route::get('video/{videoId}', [VideoController::class, 'getById']);
Route::get('timecodes/{videoId}', [VideoController::class, 'timecodes']);
Route::middleware('auth:sanctum')
    ->middleware('isCreator')
    ->middleware('verified')
    ->get('questions/{videoId}', [VideoController::class, 'questions']);

//questionController
Route::get('question/{id}', [QuestionController::class, 'getById']);

Route::post('question/checkAnswers/{id}', [QuestionController::class, 'checkAnswers']);

Route::middleware('auth:sanctum')
    ->middleware('isCreator')
    ->middleware('verified')
    ->post('question', [QuestionController::class, 'storeQuestion']);

Route::middleware('auth:sanctum')
    ->middleware('isCreator')
    ->middleware('verified')
    ->post('deleteQuestion/{id}', [QuestionController::class, 'deleteQuestion']);

Route::get('score/', [QuestionController::class, 'score']);

//userController

Route::middleware('auth:sanctum')->get('user', [UserController::class, 'index']);

//admin

Route::middleware('auth:sanctum')
    ->middleware('isCreator')
    ->middleware('verified')
    ->get('allowed-email', [VideoDataController::class, 'getAllowedEmail']);

Route::middleware('auth:sanctum')
    ->middleware('isAdmin')
    ->middleware('verified')
    ->post('allowed-email', [VideoDataController::class, 'setAllowedEmail']);

Route::middleware('auth:sanctum')
    ->middleware('isAdmin')
    ->middleware('verified')
    ->delete('allowed-email/{id}', [VideoDataController::class, 'deleteAllowedEmail']);

// rights

Route::middleware('auth:sanctum')
    ->middleware('isAdmin')
    ->middleware('verified')
    ->get('users', [UserController::class, 'getAll']);

Route::middleware('auth:sanctum')
    ->middleware('isAdmin')
    ->middleware('verified')
    ->post('makeEditor/{id}', [UserController::class, 'makeEditor']);


// user login and signup
Route::post('userlogin', [UserController::class, 'userLogin']);
Route::post('usersignup', [UserController::class, 'userSignUp']);

//scripts to change database

// Route::get('transfer', [VideoController::class, 'transferScript']);
