<?php

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

Route::get('/leaderboard', [LeaderboardController::class, 'getLeaderboard']);
Route::post('/users', [LeaderboardController::class, 'addUser']);
Route::delete('/users/{id}', [LeaderboardController::class, 'deleteUser']);
Route::patch('/users/{id}/points', [LeaderboardController::class, 'updatePoints']);
Route::get('/users/{id}', [LeaderboardController::class, 'getUserDetails']);
Route::post('/reset-scores', [LeaderboardController::class, 'resetScores']);
Route::get('/users-grouped', [LeaderboardController::class, 'getUsersGroupedByScore']);

