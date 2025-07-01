<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

//start game
// Rota de get 1.nome da rota 2.de onde vem o metodo 3.nome do metodo 4.nome da rota
Route::get('/', [MainController::class, 'startGame'])->name('start_game');
Route::post('/', [MainController::class, 'prepareGame'])->name('prepare_game');

//in game
Route::get('/game', [MainController::class, 'game'])->name('game');
Route::get('/answer/{answer}', [MainController::class, 'answer'])->name('answer');
Route::get('/next_question', [MainController::class, 'nextQuestion'])->name('next_question');

//game over
Route::get('/show_results', [MainController::class, 'showResults'])->name('show_results');
