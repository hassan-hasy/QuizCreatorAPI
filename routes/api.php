<?php

use App\Http\Controllers\Questions\QuestionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get("questions", [QuestionsController::class, 'index'])->name('questions');
Route::get("questions/{id}", [QuestionsController::class, 'show'])->name('questions');
Route::post("questions", [QuestionsController::class, 'store'])->name('questions');
