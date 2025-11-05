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
    $sprints = $project->sprints()->orderBy('start_date')->get();

    $sprintId = $request->query('sprint');
    $sprint = $sprintId ? $sprints->find($sprintId) : $sprints->first();

    $tasks = collect();

    if ($sprint) {
        // Tâches liées aux epics du sprint
        $tasks = $sprint->epics()->with('tasks')->get()->flatMap(function ($epic) {
            return $epic->tasks;
        });

        // Tâches non liées à un epic
        $tasks = $tasks->merge(Task::where('sprint_id', $sprint->id_sprint)
            ->whereNull('epic_id')
            ->get());
    }

    $tasksByStatus = [
        'todo' => $tasks->where('status', 'todo'),
        'in_progress' => $tasks->where('status', 'in_progress'),
        'done' => $tasks->where('status', 'done'),
    ];

    // 🔹 Comptage des tâches par colonne
    $tasksCount = [
        'todo' => $tasksByStatus['todo']->count(),
        'in_progress' => $tasksByStatus['in_progress']->count(),
        'done' => $tasksByStatus['done']->count(),
    ];

    return view('kanban.show', compact('project', 'sprint', 'sprints', 'tasksByStatus', 'tasksCount'));
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

public function moveTask(Request $request, Task $task)
{
    $request->validate([
        'status' => 'required|in:todo,in_progress,done',
    ]);
    $task->update(['status' => $request->status]);

    return response()->json(['success' => true]);
}

public function reporting(Project $project)
{
    $sprints = $project->sprints()->with(['epics.tasks', 'tasks'])->get();
    $tasks = collect();
    foreach ($sprints as $sprint) {
        foreach ($sprint->epics as $epic) {
            $tasks = $tasks->merge($epic->tasks);
        }
        $tasks = $tasks->merge($sprint->tasks); // CA MARCHERA ICI
    }

    $count_todo = $tasks->where('status', 'todo')->count();
    $count_progress = $tasks->where('status', 'in_progress')->count();
    $count_done = $tasks->where('status', 'done')->count();
    $count_total = $tasks->count();

    $members = $project->members;

    return view('projects.reporting', compact(
        'project', 'tasks', 'count_todo', 'count_progress', 'count_done', 'count_total', 'members'
    ));

}

}
