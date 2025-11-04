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
        $projects = $user->projects;
        return view('dashboard', compact('projects'));
    }

    // ==============================
    // CREATION DE PROJET
    // ==============================
    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project = Project::create([
            'name' => $request->name,
        ]);

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
public function kanban(Project $project, Request $request)
{
    // Récupérer tous les sprints du projet triés par date
    $sprints = $project->sprints()->orderBy('start_date')->get();

    // Sprint sélectionné ou premier par défaut
    $sprintId = $request->query('sprint'); // via GET
    $sprint = $sprintId ? $sprints->find($sprintId) : $sprints->first();

    if (!$sprint) {
        return redirect()->route('dashboard')->with('error', 'Aucun sprint trouvé pour ce projet.');
    }

    // Récupérer toutes les tâches de ce sprint via les epics
    $tasks = $sprint->epics()->with('tasks')->get()->flatMap(function ($epic) {
        return $epic->tasks;
    });

    // Ajouter les tâches du sprint qui n'ont pas d'epic
    $tasks = $tasks->merge(Task::where('sprint_id', $sprint->id_sprint)
        ->whereNull('epic_id')
        ->get());

    // Grouper les tâches par statut
    $tasksByStatus = [
        'todo' => $tasks->where('status', 'todo'),
        'in_progress' => $tasks->where('status', 'in_progress'),
        'done' => $tasks->where('status', 'done'),
    ];

    return view('kanban.show', compact('project', 'sprint', 'sprints', 'tasksByStatus'));
}

public function storeKanbanTask(Request $request, Project $project, $sprintId)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'status' => 'required|in:todo,in_progress,done',
    ]);

    Task::create([
        'title' => $request->title,
        'status' => $request->status,
        'sprint_id' => $sprintId,
    ]);

    return back()->with('success', 'Tâche ajoutée au Kanban !');
}

}
