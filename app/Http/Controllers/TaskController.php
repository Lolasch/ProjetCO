<?php

namespace App\Http\Controllers;

use App\Models\Epic;
use App\Models\Task;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function store(Request $request, Epic $epic)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:todo,in_progress,done',
            'assigned_to' => 'nullable|exists:users,id_user',
            'due_date' => 'required|date',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'epic_id' => $epic->id_epic,
            'sprint_id' => $epic->sprint_id,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('projects.roadmap', $epic->sprint->project_id)
                         ->with('success', 'Tâche créée avec succès !');
    }

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
            'due_date' => 'required|date',
        ]);

        $project = $task->epic
            ? $task->epic->sprint->project
            : ($task->sprint ? $task->sprint->project : null);
        $userProject = $project ? $project->members->firstWhere('id_user', auth()->id()) : null;
        $role = $userProject ? $userProject->pivot->role : null;
        if ($role !== 'manager') {
            unset($data['assigned_to']);
        }
        $task->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'epic_id' => $data['epic_id'] ?? null,
            'status' => $data['status'] ?? $task->status,
            'assigned_to' => $data['assigned_to'] ?? $task->assigned_to,
            'due_date' => $data['due_date'],
        ]);

        if ($task->assigned_to && $task->assigned_to != auth()->id()) {
            $notif = Notification::create([
                'user_id' => $task->assigned_to,
                'type' => 'update',
                'task_id' => $task->id_task,
                'project_id' => $project ? $project->id_project : null,
                'title' => "La tâche '{$task->title}' a été mise à jour",
                'body' => "Modification effectuée par " . auth()->user()->name,
                'is_read' => false,
            ]);
            $user = $task->assignee;
            if($user && $user->email){
                Mail::raw(
                    $notif->title . "\n\n" . ($notif->body ?? ""),
                    function($message) use ($user) {
                        $message->to($user->email)
                                ->subject('Notification ProjetCO');
                    }
                );
            }
        }

        $projectId = $task->sprint->project_id ?? $task->epic->project_id ?? null;
        return redirect()->route('projects.kanban', $projectId)
            ->with('success', 'Tâche mise à jour avec succès !');
    }

    public function destroy(Task $task)
    {
        $projectId = $task->sprint->project_id ?? $task->epic->project_id ?? null;
        $task->delete();
        return redirect()->route('projects.kanban', $projectId)
            ->with('success', 'Tâche supprimée avec succès !');
    }
}
