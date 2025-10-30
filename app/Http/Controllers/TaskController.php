<?php

namespace App\Http\Controllers;

use App\Models\Epic;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // 📘 Formulaire création d’une tâche
    public function create(Epic $epic)
    {
        return view('tasks.create', compact('epic'));
    }

    // 📗 Sauvegarde d’une tâche
    public function store(Request $request, Epic $epic)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $epic->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'epic_id' => $epic->id_epic,
            'sprint_id' => $epic->sprint_id,
        ]);

        return redirect()->route('projects.roadmap', $epic->project_id)
                         ->with('success', 'Tâche créée avec succès !');
    }

    // ✏️ Formulaire de modification
// ✏️ Formulaire de modification
public function edit(Task $task)
{
    // On récupère toutes les epics du même projet pour le menu déroulant
    $epics = \App\Models\Epic::where('project_id', $task->epic->project_id ?? null)->get();

    return view('tasks.edit', compact('task', 'epics'));
}


    // 💾 Mise à jour d’une tâche
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('projects.roadmap', $task->epic->project_id)
                         ->with('success', 'Tâche mise à jour avec succès !');
    }

    // ❌ Suppression d’une tâche
    public function destroy(Task $task)
    {
        $projectId = $task->epic->project_id;
        $task->delete();

        return redirect()->route('projects.roadmap', $projectId)
                         ->with('success', 'Tâche supprimée avec succès !');
    }
}
