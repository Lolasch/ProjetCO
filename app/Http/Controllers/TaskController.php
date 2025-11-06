<?php

namespace App\Http\Controllers;

use App\Models\Epic;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Création d’une tâche depuis roadmap (epic)
    public function create(Epic $epic)
    {
        $project = $epic->sprint->project;
        $associates = $project->members()->wherePivot('role', 'associate')->get();
        return view('tasks.create', compact('epic', 'associates'));
    }

    public function store(Request $request, Epic $epic)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:todo,in_progress,done',
            'assigned_to' => 'nullable|exists:users,id_user',
        ]);

        Task::create([
            'title' => $request->title,
            'status' => $request->status,
            'epic_id' => $epic->id_epic,
            'sprint_id' => $epic->sprint_id,
            'assigned_to' => $request->assigned_to,
        ]);

        return redirect()->route('projects.roadmap', $epic->sprint->project_id)
                         ->with('success', 'Tâche créée avec succès !');
    }

    // Édition d’une tâche (modal Kanban)
    public function edit(Task $task)
    {
        $project = $task->epic
            ? $task->epic->sprint->project
            : ($task->sprint ? $task->sprint->project : null);
        $associates = $project ? $project->members()->wherePivot('role', 'associate')->get() : collect();

        return view('tasks.edit', compact('task', 'associates'));
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'nullable|in:todo,in_progress,done',
            'description' => 'nullable|string',
            'epic_id' => 'nullable|exists:epics,id_epic',
            'assigned_to' => 'nullable|exists:users,id_user',
        ]);

        // On protège l'assignation : seul le chef peut modifier assigned_to
        $project = $task->epic
            ? $task->epic->sprint->project
            : ($task->sprint ? $task->sprint->project : null);
        $userProject = $project ? $project->members->firstWhere('id_user', auth()->id()) : null;
        $role = $userProject ? $userProject->pivot->role : null;

        if($role !== 'manager'){
            unset($data['assigned_to']);
        }

        $task->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'epic_id' => $data['epic_id'] ?? null,
            'status' => $data['status'] ?? $task->status,
            'assigned_to' => $data['assigned_to'] ?? $task->assigned_to,
        ]);

        $projectId = $task->sprint->project_id ?? $task->epic->project_id ?? null;
        return redirect()->route('projects.kanban', $projectId)
            ->with('success', 'Tâche mise à jour avec succès !');
    }

    // Suppression d’une tâche
    public function destroy(Task $task)
    {
        $projectId = $task->sprint->project_id ?? $task->epic->project_id ?? null;
        $task->delete();

        return redirect()->route('projects.kanban', $projectId)
            ->with('success', 'Tâche supprimée avec succès !');
    }
}
