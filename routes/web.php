<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyController;

Route::get('/', function () {return view('welcome');});
Route::get('/createInt', [MyController::class, 'createIntent']);
Route::get('/link', [MyController::class, 'link']);
Route::get('/sponsor', [MyController::class, 'sponsor']);


Route::post('/submitLink', [MyController::class, 'submitLink']);
Route::post('/createPaymentIntent', [MyController::class, 'createPaymentIntent']);
Route::post('/createPaymentMethod', [MyController::class, 'createPaymentMethod']);
Route::post('/attach', [MyController::class, 'attach']);