<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();             // l'utilisateur connecté
        $projects = $user->projects;        // ses projets via la relation belongsToMany

        return view('dashboard', compact('projects'));
    }
}
