<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\EpicController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\ReleaseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientAccessController;


// --------------------
// Authentification
// --------------------
Route::get('/', fn() => view('home'))->name('home');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --------------------
// Dashboard
// --------------------
Route::get('/dashboard', [ProjectController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

// Projets
Route::resource('projects', ProjectController::class)->middleware('auth');
Route::get('/projects/{project}/reporting', [ProjectController::class, 'reporting'])->name('projects.reporting');
Route::post('/projects/{project}/add-member', [ProjectController::class, 'addMember'])->name('projects.addMember');
Route::get('/users/search', [UserController::class, 'searchByEmail'])->name('users.searchByEmail');
Route::get('/share/{project}/{code}', [ProjectController::class, 'sharedView'])->name('projects.sharedView');

// --------------------
// Roadmap
// --------------------
Route::get('/projects/{project}/roadmap', [RoadmapController::class, 'show'])
    ->middleware('auth')
    ->name('projects.roadmap');

// --------------------
// Sprints (liés à un projet)
// --------------------
Route::middleware('auth')->group(function() {
    Route::get('/projects/{project}/sprints/create', [SprintController::class, 'create'])->name('sprints.create');
    Route::post('/projects/{project}/sprints', [SprintController::class, 'store'])->name('sprints.store');
    Route::get('/sprints/{sprint}/edit', [SprintController::class, 'edit'])->name('sprints.edit');
    Route::put('/sprints/{sprint}', [SprintController::class, 'update'])->name('sprints.update');
    Route::delete('/sprints/{sprint}', [SprintController::class, 'destroy'])->name('sprints.destroy');
});

// --------------------
// Epics (liés à un sprint)
// --------------------
Route::middleware('auth')->group(function() {
    Route::get('/sprints/{sprint}/epics/create', [EpicController::class, 'create'])->name('epics.create');
    Route::post('/sprints/{sprint}/epics', [EpicController::class, 'store'])->name('epics.store');
    Route::get('/epics/{epic}/edit', [EpicController::class, 'edit'])->name('epics.edit');
    Route::put('/epics/{epic}', [EpicController::class, 'update'])->name('epics.update');
    Route::delete('/epics/{epic}', [EpicController::class, 'destroy'])->name('epics.destroy');
});

// --------------------
// Releases (liés à un projet)
// --------------------
Route::middleware('auth')->group(function() {
    Route::get('/projects/{project}/releases/create', [ReleaseController::class, 'create'])->name('releases.create');
    Route::post('/projects/{project}/releases', [ReleaseController::class, 'store'])->name('releases.store');
    Route::delete('/releases/{release}', [ReleaseController::class, 'destroy'])->name('releases.destroy');
});

// --------------------
// Tâches (liées à un epic)
// --------------------
Route::middleware('auth')->group(function() {
    Route::get('/epics/{epic}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/epics/{epic}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

// --------------------
// Kanban (affichage + actions sprint)
// --------------------
Route::get('/projects/{project}/kanban/{sprint?}', [ProjectController::class, 'kanban'])
    ->name('projects.kanban');

Route::post('/projects/{project}/kanban/{sprint}/tasks', [ProjectController::class, 'storeKanbanTask'])
    ->name('kanban.tasks.store');

// DRAG & DROP (update AJAX)
Route::post('/kanban/tasks/{task}/move', [ProjectController::class, 'moveTask'])
    ->name('kanban.tasks.move');
