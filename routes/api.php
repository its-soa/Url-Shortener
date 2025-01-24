<?php

use App\Http\Controllers\LinkShortenerController;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;

Route::post('/test', function () {
    return response()->json(['message' => 'Test route successful']);
});

Route::post('/encode', [LinkShortenerController::class, 'encodeAPI']);
Route::post('/decode', [LinkShortenerController::class, 'decodeAPI']);