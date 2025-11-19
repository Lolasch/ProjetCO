<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();      
        $projects = $user->projects;        // relation belongsToMany avec projets

        return view('dashboard', compact('projects'));
    }
}
