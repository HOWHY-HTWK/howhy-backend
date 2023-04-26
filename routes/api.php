<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
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

//old database design

//public
Route::get('videoDatas', [VideoDataController::class, 'index']);                              //replaced
Route::get('videoDatas/byVideoId/{id}', [VideoDataController::class, 'showByVideoId']);       //replaced 
Route::post('videoDatas/checkAnswers/{id}', [VideoDataController::class, 'checkAnswers']);    //replaced 
Route::get('videoDatas/list/', [VideoDataController::class, 'getVideoList']);                 //replaced

//editor
Route::middleware('auth:sanctum')->get('videoDatas/all/', [VideoDataController::class, 'getVideoList']);    //replaced
Route::middleware('auth:sanctum')->post('videoDatas', [VideoDataController::class, 'store']);               //replaced

//------------ New Database Design

//general
Route::middleware('auth:sanctum')->middleware('isCreator')->get('check', [VideoDataController::class, 'check']);

//videoController
Route::get('videos', [VideoController::class, 'index']);                                    //tested
Route::get('video/{videoId}', [VideoController::class, 'getById']);                         //tested
Route::get('timecodes/{videoId}', [VideoController::class, 'timecodes']);                   //tested
Route::middleware('auth:sanctum')->middleware('isCreator')->get('questions/{videoId}', [VideoController::class, 'questions']);                   //tested

//questionController
Route::get('question/{id}', [QuestionController::class, 'getById']);                        //tested
Route::post('question/checkAnswers/{id}', [QuestionController::class, 'checkAnswers']);     //tested but not with user
Route::middleware('auth:sanctum')->middleware('isCreator')->post('question', [QuestionController::class, 'storeQuestion']);       
Route::middleware('auth:sanctum')->middleware('isCreator')->post('deleteQuestion/{id}', [QuestionController::class, 'deleteQuestion']);       

//score
Route::get('score/', [QuestionController::class, 'score']);       

//admin
Route::middleware('auth:sanctum')->middleware('isCreator')->get('allowed-email', [VideoDataController::class, 'getAllowedEmail']);
Route::middleware('auth:sanctum')->middleware('isCreator')->post('allowed-email', [VideoDataController::class, 'setAllowedEmail']);
Route::middleware('auth:sanctum')->middleware('isCreator')->delete('allowed-email/{id}', [VideoDataController::class, 'deleteAllowedEmail']);

//old
// Route::post('user-answer', [UserController::class, 'storeUserAnswer']);

//temp
// Route::get('transfer', [VideoController::class, 'transferScript']);
// Route::get('fix', [QuestionController::class, 'fixJsonInTable']);