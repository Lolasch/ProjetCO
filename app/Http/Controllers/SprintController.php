<?php

namespace App\Http\Controllers;

use App\Models\Sprint;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SprintController extends Controller
{
    // Formulaire création sprint
    public function create(Project $project)
    {
        return view('sprints.create', compact('project'));
    }

    // Stocker le sprint
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->sprints()->create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('projects.roadmap', $project->id_project)
                         ->with('success', 'Sprint créé avec succès !');
    }

    // Optionnel : édition, mise à jour et suppression peuvent être ajoutées plus tard
}
