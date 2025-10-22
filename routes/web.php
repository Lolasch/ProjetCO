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

use App\Http\Controllers\ProjectController;


// Dashboard (déjà existant)
Route::get('/dashboard', [ProjectController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

// Créer un projet
Route::get('/projects/create', [ProjectController::class, 'create'])
    ->middleware('auth')
    ->name('projects.create');
Route::post('/projects', [ProjectController::class, 'store'])
    ->middleware('auth')
    ->name('projects.store');

// Modifier un projet
Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])
    ->middleware('auth')
    ->name('projects.edit');
Route::put('/projects/{project}', [ProjectController::class, 'update'])
    ->middleware('auth')
    ->name('projects.update');

// Supprimer un projet
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])
    ->middleware('auth')
    ->name('projects.destroy');

// Créer un sprint
Route::get('/projects/{project}/sprints/create', [SprintController::class, 'create'])->name('sprints.create');
Route::post('/projects/{project}/sprints', [SprintController::class, 'store'])->name('sprints.store');


// Créer un Epic
use App\Http\Controllers\EpicController;

Route::get('/projects/{project}/epics/create', [EpicController::class, 'create'])
    ->middleware('auth')
    ->name('epics.create');

Route::post('/projects/{project}/epics', [EpicController::class, 'store'])
    ->middleware('auth')
    ->name('epics.store');

use App\Http\Controllers\TaskController;

// Routes pour les tâches
Route::get('/sprints/{sprint}/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/sprints/{sprint}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::post('/sprints/{sprint}/tasks', [TaskController::class, 'store'])->name('tasks.store');
