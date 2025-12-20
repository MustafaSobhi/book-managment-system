<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:sanctum');

Route::apiResource('books',BookController::class)->only(['index','show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/books',[BookController::class,'store']);
    Route::put('/books/{book}',[BookController::class,'update']);
    Route::delete('/books/{book}',[BookController::class,'destroy']);
});