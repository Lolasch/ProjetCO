<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Page d’accueil avec choix connexion / inscription
Route::get('/', function () {
    return view('home');
})->name('home');

// Inscription
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Connexion
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Déconnexion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//bord
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])
     ->middleware('auth')
     ->name('dashboard');
