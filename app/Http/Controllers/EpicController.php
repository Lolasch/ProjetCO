<?php

namespace App\Http\Controllers;

use App\Models\Epic;
use App\Models\Sprint;
use Illuminate\Http\Request;

class EpicController extends Controller
{
    public function create(Sprint $sprint)
    {
        return view('epics.create', compact('sprint'));
    }

    public function store(Request $request, Sprint $sprint)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $sprint->epics()->create([
            'name' => $request->name,
            'project_id' => $sprint->project_id,
        ]);

        return redirect()->route('projects.roadmap', $sprint->project->id_project)
                         ->with('success', 'Epic créé avec succès !');
    }

    public function edit(Epic $epic)
    {
        return view('epics.edit', compact('epic'));
    }

    public function update(Request $request, Epic $epic)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $epic->update([
            'name' => $request->name,
        ]);

        return redirect()->route('projects.roadmap', $epic->project_id)
                         ->with('success', 'Epic mis à jour avec succès !');
    }

    public function destroy(Epic $epic)
    {
        $epic->delete();

        return redirect()->route('projects.roadmap', $epic->project_id)
                         ->with('success', 'Epic supprimé avec succès !');
    }
}
