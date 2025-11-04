@extends('layouts.app')

@section('title', 'Liste des tâches')

@section('content')
<div class="px-8 py-6 max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-center">Tâches du Sprint : {{ $sprint->name }}</h1>

    <div class="flex justify-between mb-6">
        <a href="{{ route('tasks.create', $sprint->id_sprint) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ➕ Ajouter une tâche
        </a>

        <a href="{{ route('projects.roadmap', $sprint->project_id) }}"
           class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">
            ⬅ Retour à la roadmap
        </a>
    </div>

    @if($tasks->isEmpty())
        <p class="text-gray-500 text-center">Aucune tâche pour ce sprint.</p>
    @else
        <ul class="space-y-4">
            @foreach ($tasks as $task)
                <li class="border rounded-lg p-4 shadow hover:shadow-md transition">
                    <div class="flex justify-between items-center mb-2">
                        <strong class="text-lg">{{ $task->title }}</strong>
                        <span class="text-sm px-2 py-1 rounded
                            {{ $task->status == 'todo' ? 'bg-red-200 text-red-800' : ($task->status == 'in_progress' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800') }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>

                    <p class="text-gray-700 mb-1">{{ $task->description }}</p>
                    <p class="text-sm text-gray-500">Epic : {{ $task->epic->name ?? 'Aucun' }}</p>

                    <div class="flex justify-between mt-2 text-sm">
                        <a href="{{ route('tasks.edit', $task->id_task) }}"
                           class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500">
                            ✏️ Modifier
                        </a>

                        <form action="{{ route('tasks.destroy', $task->id_task) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                ❌ Supprimer
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
