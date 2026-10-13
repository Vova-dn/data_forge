<?php

use App\Http\Controllers\DatasetController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [LoginController::class, 'login']);

Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');

Route::get('/datasets/{id}', [DatasetController::class, 'getDatasets']);

Route::post('/datasets', [DatasetController::class, 'postDatasets']);
