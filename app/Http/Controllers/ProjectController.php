<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use App\Models\Epic;
use App\Models\Task;
use App\Models\User;
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
        $projects = $user->projects; // Assoc, manager, client

        // Calcule la progression :
        foreach ($projects as $project) {
            $sprints = $project->sprints()->with(['epics.tasks', 'tasks'])->get();
            $tasks = collect();
            foreach ($sprints as $sprint) {
                foreach ($sprint->epics as $epic) {
                    $tasks = $tasks->merge($epic->tasks);
                }
                $tasks = $tasks->merge($sprint->tasks);
            }
            $total = $tasks->count();
            $done = $tasks->where('status', 'done')->count();
            $project->progress = $total > 0 ? round($done * 100 / $total) : 0;
        }
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
        $request->validate([ 'name' => 'required|string|max:255', ]);
        $project = Project::create([ 'name' => $request->name, ]);
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
        $request->validate([ 'name' => 'required|string|max:255', ]);
        $project->update([ 'name' => $request->name, ]);
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
    // KANBAN AVEC FILTRAGE
    // ==============================
    public function kanban(Project $project, Request $request)
    {
        $sprints = $project->sprints()->orderBy('start_date')->get();
        $sprintId = $request->query('sprint');
        $sprint = $sprintId ? $sprints->find($sprintId) : $sprints->first();

        $tasks = collect();

        if ($sprint) {
            // Récupère toutes les tâches du sprint (avec épics et sans épics)
            $tasks = $sprint->epics()->with('tasks')->get()->flatMap(function ($epic) {
                return $epic->tasks;
            });
            $tasks = $tasks->merge(Task::where('sprint_id', $sprint->id_sprint)
                ->whereNull('epic_id')
                ->get());
        }

        // ========== APPLIQUE LES FILTRES ==========

        // 1. Filtrer par mot-clé (titre ou description)
        $keyword = $request->query('keyword', '');
        if ($keyword) {
            $keyword = strtolower($keyword);
            $tasks = $tasks->filter(function ($task) use ($keyword) {
                $searchable = strtolower($task->title . ' ' . ($task->description ?? ''));
                return strpos($searchable, $keyword) !== false;
            });
        }

        // 2. Filtrer par responsable (assigned_to)
        $assignee = $request->query('assignee', '');
        if ($assignee) {
            $tasks = $tasks->where('assigned_to', $assignee);
        }

        // 3. Filtrer par date d'échéance
        $dueDate = $request->query('due_date', '');
        if ($dueDate) {
            $tasks = $tasks->filter(function ($task) use ($dueDate) {
                return $task->due_date && $task->due_date <= $dueDate;
            });
        }

        // 4. Filtrer par statut (optionnel)
        $status = $request->query('status', '');
        if ($status) {
            $tasks = $tasks->where('status', $status);
        }

        // ========== ORGANISE PAR STATUT ==========

        $tasksByStatus = [
            'todo' => $tasks->where('status', 'todo'),
            'in_progress' => $tasks->where('status', 'in_progress'),
            'done' => $tasks->where('status', 'done'),
        ];

        $tasksCount = [
            'todo' => $tasksByStatus['todo']->count(),
            'in_progress' => $tasksByStatus['in_progress']->count(),
            'done' => $tasksByStatus['done']->count(),
        ];

        $associates = $project->members()->wherePivot('role', 'associate')->get();

        // Récupère le rôle du membre courant
        $currentMember = $project->members->firstWhere('id_user', auth()->id());
        $currentRole = $currentMember ? $currentMember->pivot->role : null;

        // Récupère les valeurs actuelles des filtres pour repeupler les champs
        $filters = [
            'keyword' => $keyword,
            'assignee' => $assignee,
            'due_date' => $dueDate,
            'status' => $status,
        ];

        return view('kanban.show', compact(
            'project', 'sprint', 'sprints', 'tasksByStatus', 'tasksCount',
            'associates', 'currentRole', 'filters'
        ));
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

    // ==============================
    // AJOUTER UN MEMBRE
    // ==============================
    public function addMember(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id_user',
            'role' => 'required|in:manager,associate,client',
        ]);
        $project->members()->syncWithoutDetaching([
            $request->user_id => ['role' => $request->role],
        ]);
        return back()->with('success', 'Membre ajouté au projet !');
    }

    // ==============================
    // REPORTING
    // ==============================
    public function reporting(Project $project)
    {
        $sprints = $project->sprints()->with(['epics.tasks', 'tasks'])->get();
        $tasks = collect();
        foreach ($sprints as $sprint) {
            foreach ($sprint->epics as $epic) {
                $tasks = $tasks->merge($epic->tasks);
            }
            $tasks = $tasks->merge($sprint->tasks);
        }
        $count_todo = $tasks->where('status', 'todo')->count();
        $count_progress = $tasks->where('status', 'in_progress')->count();
        $count_done = $tasks->where('status', 'done')->count();
        $count_total = $tasks->count();
        $members = $project->members;
        $user_candidates = User::whereNotIn('id_user', $members->pluck('id_user'))->get();
        return view('projects.reporting', compact(
            'project', 'tasks', 'count_todo', 'count_progress', 'count_done', 'count_total', 'members', 'user_candidates'
        ));
    }

    public function sharedView(Project $project, $code)
    {
        $expected = 'CLIENT-' . strtoupper(substr(hash('crc32', $project->id_project . 'SECRET_SALT'),0,8));
        if ($code !== $expected) {
            abort(404);
        }
        // Prépare toutes les données nécessaires pour la vue reporting readonly
        $sprints = $project->sprints()->with('epics.tasks')->get();
        $members = $project->members;
        $tasks = collect();
        foreach ($sprints as $sprint) {
            foreach ($sprint->epics as $epic) {
                $tasks = $tasks->merge($epic->tasks);
            }
            $tasks = $tasks->merge($sprint->tasks);
        }
        $count_todo = $tasks->where('status', 'todo')->count();
        $count_progress = $tasks->where('status', 'in_progress')->count();
        $count_done = $tasks->where('status', 'done')->count();
        $count_total = $tasks->count();

        return view('projects.viewer', compact(
            'project', 'sprints', 'members',
            'count_todo', 'count_progress', 'count_done', 'count_total'
        ));
    }
}
