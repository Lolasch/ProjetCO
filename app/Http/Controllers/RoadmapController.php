<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Release;
use Illuminate\Http\Request;

class RoadmapController extends Controller
{
    public function show(Project $project)
    {
        $sprints = $project->sprints()->with('epics.tasks')->get();

        $releases = Release::where('project_id', $project->id_project)->get();

        $currentMember = $project->members->firstWhere('id_user', auth()->id());
        $currentRole = $currentMember ? $currentMember->pivot->role : null;

        return view('roadmap.show', compact('project', 'sprints', 'releases', 'currentRole'));
    }
}
