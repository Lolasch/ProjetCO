@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4 text-center text-[#1c2352]">Créer une nouvelle tâche</h1>
    <form action="{{ route('tasks.store', $epic->id_epic) }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="title" class="block font-bold mb-1">Titre :</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                class="rounded px-2 py-1 border w-full">
        </div>

        <div>
            <label for="description" class="block font-bold mb-1">Description :</label>
            <textarea name="description" id="description" rows="2"
                class="rounded px-2 py-1 border w-full">{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="status" class="block font-bold mb-1">Statut :</label>
            <select name="status" id="status" class="rounded px-2 py-1 border w-full">
                <option value="todo">À faire</option>
                <option value="in_progress">En cours</option>
                <option value="done">Terminée</option>
            </select>
        </div>

        <div>
            <label for="assigned_to" class="block font-bold mb-1">Assigner à un associé :</label>
            <select name="assigned_to" id="assigned_to" class="rounded px-2 py-1 border w-full">
                <option value="">-- Aucun --</option>
                @foreach($associates as $user)
                    <option value="{{ $user->id_user }}" @if(old('assigned_to') == $user->id_user) selected @endif>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Créer la tâche</button>
    </form>
</div>
@endsection
