<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // ==============================
    // DASHBOARD
    // ==============================
    public function dashboard()
    {
        $user = Auth::user();
        $projects = $user->projects; // tous les projets liés à l'utilisateur
        return view('dashboard', compact('projects'));
    }

    // ==============================
    // CREATION DE PROJET
    // ==============================
    public function create()
    {
        return view('projects.create');
    }

    // Sauvegarder un projet
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

    // ==============================
    // MODIFICATION DE PROJET
    // ==============================
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    // Mettre à jour un projet
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

    // ==============================
    // SUPPRESSION DE PROJET
    // ==============================
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('dashboard')->with('success', 'Projet supprimé avec succès.');
    }

    // ==============================
    // KANBAN
    // ==============================
    public function kanban(Project $project)
    {
        // On récupère le sprint le plus proche dans le temps
        $currentSprint = $project->sprints()
            ->where('end_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->first();

        // Si aucun sprint n'existe, redirection
        if (!$currentSprint) {
            return redirect()->route('projects.roadmap', $project->id_project)
                            ->with('error', 'Aucun sprint disponible pour ce projet.');
        }

        // Récupérer toutes les tâches du sprint
        $tasks = Task::whereHas('epic', function ($query) use ($currentSprint) {
            $query->where('sprint_id', $currentSprint->id);
        })->with('epic')->get();

        // Regrouper les tâches par statut
        $tasksByStatus = [
            'todo' => $tasks->where('status', 'todo'),
            'in_progress' => $tasks->where('status', 'in_progress'),
            'done' => $tasks->where('status', 'done'),
        ];

        // Récupérer tous les sprints du projet
        $allSprints = $project->sprints()->orderBy('start_date', 'asc')->get();

        return view('kanban.show', compact('project', 'currentSprint', 'tasksByStatus', 'allSprints'));
    }
}
