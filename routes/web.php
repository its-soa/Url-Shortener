<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkShortenerController;

Route::get('/', function () {
    return view('url-shortener');
});

Route::get('/url', function () {
    return view('url-shortener');
});


Route::post('/encode', [LinkShortenerController::class, 'encode'])->name('encode');
Route::post('/decode', [LinkShortenerController::class, 'decode'])->name('decode');