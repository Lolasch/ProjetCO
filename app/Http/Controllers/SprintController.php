<?php

namespace App\Http\Controllers;

use App\Models\Sprint;
use App\Models\Project;
use Illuminate\Http\Request;

class SprintController extends Controller
{
    public function create(Project $project)
    {
        return view('sprints.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'color' => 'nullable|string|max:7',
        ]);

        $project->sprints()->create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'color' => $request->color ?? '#1e40af',
        ]);

        return redirect()->route('projects.roadmap', $project->id_project)
                         ->with('success', 'Sprint créé avec succès !');
    }

    public function edit(Sprint $sprint)
    {
        return view('sprints.edit', compact('sprint'));
    }

    public function update(Request $request, Sprint $sprint)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'color' => 'nullable|string|max:7',
        ]);

        $sprint->update($request->only('name', 'start_date', 'end_date', 'color'));

        return redirect()->route('projects.roadmap', $sprint->project_id)
                         ->with('success', 'Sprint mis à jour avec succès !');
    }

    public function destroy(Sprint $sprint)
    {
        $sprint->delete();

        return back()->with('success', 'Sprint supprimé avec succès.');
    }
}
