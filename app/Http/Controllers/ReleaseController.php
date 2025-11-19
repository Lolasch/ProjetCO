<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Release;
use Illuminate\Http\Request;

class ReleaseController extends Controller
{
    public function create(Project $project)
    {
        return view('releases.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'release_date' => 'required|date',
            'color' => 'nullable|string|max:255'
        ]);

        Release::create([
            'project_id' => $project->id_project,
            'name' => $request->name,
            'release_date' => $request->release_date,
            'color' => $request->color
        ]);

        return redirect()->route('projects.roadmap', $project->id_project)
                         ->with('success', 'Release créée avec succès !');
    }

    public function destroy($id)
    {
        $release = Release::findOrFail($id);
        $projectId = $release->project_id;
        $release->delete();

        return redirect()->route('projects.roadmap', $projectId)
                         ->with('success', 'Release supprimée avec succès !');
    }
}
