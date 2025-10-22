@extends('layouts.app')

@section('title', 'Créer une tâche')

@section('content')
<h2>Créer une nouvelle tâche pour le sprint : {{ $sprint->name }}</h2>

<form action="{{ route('tasks.store', $sprint->id_sprint) }}" method="POST">
    @csrf
    <label>Titre :</label>
    <input type="text" name="title" required><br><br>

    <label>Description :</label>
    <textarea name="description"></textarea><br><br>

    <label>Statut :</label>
    <select name="status" required>
        <option value="todo">À faire</option>
        <option value="in_progress">En cours</option>
        <option value="done">Terminé</option>
    </select><br><br>

    <button type="submit">Créer la tâche</button>
</form>
@endsection
