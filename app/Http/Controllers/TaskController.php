<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Epic;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Formulaire création tâche
    public function create(Epic $epic)
    {
        return view('tasks.create', compact('epic'));
    }

    // Stocker la tâche
    public function store(Request $request, Epic $epic)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'assignee' => 'nullable|string|max:255', // ou user_id si tu veux relier à un user
            'due_date' => 'nullable|date',
            'status' => 'required|string|in:todo,in_progress,done',
        ]);

$epic->tasks()->create([
    'title' => $request->name,
    'assigned_to' => $request->assigned_to, // ou null si tu veux pour l'instant
    'due_date' => $request->due_date,
    'status' => $request->status ?? 'À faire',
    'epic_id' => $epic->id_epic,
    'sprint_id' => $epic->sprint_id, // si tu veux relier à un sprint
]);


        return redirect()->route('projects.roadmap', $epic->sprint->project->id_project)
                         ->with('success', 'Tâche créée avec succès !');
    }

    // Optionnel : édition, mise à jour, suppression peuvent être ajoutées plus tard
}
