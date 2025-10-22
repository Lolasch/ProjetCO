<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Sprint;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Liste des tâches d’un sprint
    public function index(Sprint $sprint)
    {
        $tasks = $sprint->tasks;
        return view('tasks.index', compact('tasks', 'sprint'));
    }

    // Formulaire de création
    public function create(Sprint $sprint)
    {
        return view('tasks.create', compact('sprint'));
    }

    // Enregistrer une nouvelle tâche
    public function store(Request $request, Sprint $sprint)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'sprint_id' => $sprint->id_sprint,
        ]);

        return redirect()->route('tasks.index', $sprint)->with('success', 'Tâche ajoutée avec succès !');
    }
}
