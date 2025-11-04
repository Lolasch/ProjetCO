@extends('layouts.app')

@section('content')
<h1>Créer une tâche pour l'Epic : {{ $epic->name }}</h1>

<form action="{{ route('tasks.store', $epic->id_epic) }}" method="POST">
    @csrf

    <label>Titre de la tâche :</label><br>
    <input type="text" name="title" value="{{ old('title') }}" required><br><br>

    <label>Description :</label><br>
    <textarea name="description">{{ old('description') }}</textarea><br><br>

    <label>Responsable :</label><br>
    <input type="text" name="assigned_to" value="{{ old('assigned_to') }}"><br><br>

    <label>Date limite :</label><br>
    <input type="date" name="due_date" value="{{ old('due_date') }}"><br><br>

    <label>Statut :</label><br>
    <select name="status">
        <option value="todo" {{ old('status') == 'todo' ? 'selected' : '' }}>À faire</option>
        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
        <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Terminé</option>
    </select><br><br>

    <button type="submit">Créer la tâche</button>
</form>

<a href="{{ route('projects.roadmap', $epic->project_id) }}" class="mt-4 inline-block text-blue-500 hover:underline">
    ⬅ Retour à la roadmap
</a>
@endsection
