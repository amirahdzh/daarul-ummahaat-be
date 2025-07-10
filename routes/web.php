<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SwaggerController;

Route::get('/', function () {
    return view('welcome');
});

// Swagger Documentation Routes
Route::get('/api/docs', [SwaggerController::class, 'ui'])->name('api.docs');
Route::get('/api/swagger.yaml', [SwaggerController::class, 'yaml'])->name('api.swagger.yaml');
Route::get('/api/swagger.json', [SwaggerController::class, 'json'])->name('api.swagger.json');
