<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Release;
use Illuminate\Http\Request;

class RoadmapController extends Controller
{
    public function show(Project $project)
    {
        // 🧭 Récupérer les sprints avec leurs epics et tâches
        $sprints = $project->sprints()->with('epics.tasks')->get();

        // 🚀 Récupérer les releases liées à ce projet
        $releases = Release::where('project_id', $project->id_project)->get();

        // ➕ Récupérer le rôle du membre courant dans ce projet
        $currentMember = $project->members->firstWhere('id_user', auth()->id());
        $currentRole = $currentMember ? $currentMember->pivot->role : null;

        // 🔁 Envoyer à la vue
        return view('roadmap.show', compact('project', 'sprints', 'releases', 'currentRole'));
    }
}
