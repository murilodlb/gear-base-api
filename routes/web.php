<?php

use Illuminate\Support\Facades\Route;

// Rota raiz (Health check básico da API)
Route::get('/', function () {
    return response()->json([
        'app'     => 'GearBase API',
        'status'  => 'operational',
        'version' => '1.0.0',
    ]);
});
