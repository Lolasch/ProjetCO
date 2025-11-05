<?php

namespace App\Http\Controllers;

use App\Models\Epic;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function edit(Task $task)
    {
        $epics = Epic::where('project_id', $task->epic->project_id ?? null)->get();
        return view('tasks.edit', compact('task', 'epics'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:todo,in_progress,done',
            'description' => 'nullable|string',
            'epic_id' => 'nullable|exists:epics,id_epic',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'epic_id' => $request->epic_id ?: null,
        ]);

        return redirect()->back()->with('success', 'Tâche mise à jour avec succès !');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Tâche supprimée avec succès !');
    }
}
