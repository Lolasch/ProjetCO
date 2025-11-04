@extends('layouts.app')

@section('content')
<div class="px-8 py-6">
    <h1 class="text-3xl font-bold mb-6 text-center">Kanban - {{ $project->name }}</h1>

    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">← Retour aux projets</a>
    </div>

    <div class="flex space-x-4 overflow-x-auto">

        @foreach($lists as $list)
            <div class="bg-gray-100 rounded-2xl p-4 w-80 flex flex-col">
                <h2 class="text-xl font-bold mb-4">{{ $list->title }}</h2>

                <div class="flex flex-col space-y-2">
                    @foreach($list->tasks as $task)
                        <div class="bg-white rounded p-2 shadow cursor-move">
                            {{ $task->title }}
                        </div>
                    @endforeach
                </div>

                <form action="{{ route('kanban.tasks.store', ['project' => $project->id_project, 'list' => $list->id]) }}" method="POST" class="mt-2">
                    @csrf
                    <input type="text" name="title" placeholder="Titre de la tâche" class="w-full border rounded p-1 text-sm mb-2" required>
                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-sm w-full">➕ Ajouter</button>
                </form>
            </div>
        @endforeach

        <div class="bg-gray-200 rounded-2xl p-4 w-80 flex items-center justify-center">
            <form action="{{ route('kanban.lists.store', $project->id_project) }}" method="POST">
                @csrf
                <input type="text" name="title" placeholder="Titre de la nouvelle liste" class="border rounded px-2 py-1 text-sm mb-2" required>
                <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-sm w-full">+ Nouvelle liste</button>
            </form>
        </div>

    </div>
</div>
@endsection
