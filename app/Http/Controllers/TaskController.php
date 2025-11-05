<?php

namespace App\Http\Controllers;

use App\Models\Epic;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // ... tes autres méthodes restent inchangées

    // 💾 Mise à jour d’une tâche (pour modal Kanban)
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'nullable|in:todo,in_progress,done', // status reste optionnel ici
            'description' => 'nullable|string',
            'epic_id' => 'nullable|exists:epics,id_epic'
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'epic_id' => $request->epic_id ?: null,
            'status' => $request->status ?? $task->status
        ]);

        // 🔁 Redirection vers la même page Kanban
        $projectId = $task->sprint->project_id ?? $task->epic->project_id ?? null;
        return redirect()->route('projects.kanban', $projectId)
            ->with('success', 'Tâche mise à jour avec succès !');
    }

    // ❌ Suppression d’une tâche
    public function destroy(Task $task)
    {
        $projectId = $task->sprint->project_id ?? $task->epic->project_id ?? null;
        $task->delete();

        return redirect()->route('projects.kanban', $projectId)
            ->with('success', 'Tâche supprimée avec succès !');
    }
}
