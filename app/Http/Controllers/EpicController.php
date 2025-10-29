<?php

namespace App\Http\Controllers;

use App\Models\Epic;
use App\Models\Sprint;
use Illuminate\Http\Request;

class EpicController extends Controller
{
    // Formulaire création d'epic
    public function create(Sprint $sprint)
    {
        return view('epics.create', compact('sprint'));
    }

    // Stocker l'epic
    public function store(Request $request, Sprint $sprint)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

      $sprint->epics()->create([
    'name' => $request->name,
    'project_id' => $sprint->project_id, // ⚡ On ajoute ça
]);


        return redirect()->route('projects.roadmap', $sprint->project->id_project)
                         ->with('success', 'Epic créé avec succès !');
    }

    // Optionnel : édition, mise à jour, suppression peuvent être ajoutées plus tard
}
