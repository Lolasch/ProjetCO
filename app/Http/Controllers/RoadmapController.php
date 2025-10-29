<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class RoadmapController extends Controller
{
    public function show(Project $project)
    {
        // Récupérer les sprints avec leurs epics
        $sprints = $project->sprints()->with('epics')->get();

        return view('roadmap.show', compact('project', 'sprints'));
    }
}
