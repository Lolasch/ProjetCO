@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Modifier la tâche : {{ $task->title }}</h1>

    <form action="{{ route('tasks.update', $task->id_task) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold mb-1">Titre</label>
            <input type="text" name="title" value="{{ old('title', $task->title) }}"
                   class="w-full border px-3 py-2 rounded">
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" class="w-full border px-3 py-2 rounded">{{ old('description', $task->description) }}</textarea>
            @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Statut</label>
            <select name="status" class="w-full border px-3 py-2 rounded">
                <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>À faire</option>
                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Terminé</option>
            </select>
            @error('status') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Epic</label>
            <select name="epic_id" class="w-full border px-3 py-2 rounded">
                <option value="">Aucun</option>
                @if($epics && $epics->count())
                    @foreach($epics as $epic)
                        <option value="{{ $epic->id }}" {{ $task->epic_id == $epic->id ? 'selected' : '' }}>
                            {{ $epic->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            @error('epic_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('projects.roadmap', $task->sprint->project_id ?? 0) }}"
               class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">⬅ Retour</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
