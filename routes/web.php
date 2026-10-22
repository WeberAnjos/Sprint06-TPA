<?php

use App\Http\Controllers\EventoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerguntaController;


Route::middleware('auth')->group(function () {
    Route::get('/eventos/create', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
});


Route::get('/', [EventoController::class, 'index'])->name('eventos.index');
Route::get('/eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');

Route::get('/register', [RegisterController::class, 'create'])->name('register.create');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/login', [LoginController::class, 'create'])->name('login.create');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('auth.logout');

Route::post('/eventos/{evento}/perguntas', [PerguntaController::class, 'store'])
    ->name('eventos.perguntas.store')
    ->middleware('auth');

Route::delete('/perguntas/{pergunta}', [PerguntaController::class, 'destroy'])
    ->name('perguntas.destroy')
    ->middleware('auth');

Route::post('/perguntas/{pergunta}/votar', [PerguntaController::class, 'votar'])
    ->name('perguntas.votar')
    ->middleware('auth');
    