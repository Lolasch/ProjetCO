<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $user = Auth::user();
        $projects = $user->projects; // tous les projets liés à l'utilisateur
        return view('dashboard', compact('projects'));
    }

    // Formulaire création
    public function create()
    {
        return view('projects.create');
    }

    // Stocker le projet
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project = Project::create([
            'name' => $request->name,
        ]);

        // Ajouter le créateur en manager
        $user = Auth::user();
        $project->members()->attach($user->id_user, ['role' => 'manager']);

        return redirect()->route('dashboard')->with('success', 'Projet créé avec succès !');
    }

    // Formulaire édition
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    // Mettre à jour le projet
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project->update([
            'name' => $request->name,
        ]);

        return redirect()->route('dashboard')->with('success', 'Projet mis à jour !');
    }

    // Supprimer un projet
    public function destroy(Project $project)
    {
     $project->delete();

        return redirect()->route('dashboard')->with('success', 'Projet supprimé avec succès.');
    }

}
