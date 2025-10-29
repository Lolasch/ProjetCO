@extends('layouts.app')

@section('content')
<h1>Créer une tâche pour l'Epic : {{ $epic->name }}</h1>

<form action="{{ route('tasks.store', $epic->id_epic) }}" method="POST">
    @csrf
    <label>Nom de la tâche :</label><br>
    <input type="text" name="name" required><br><br>

    <label>Responsable :</label><br>
    <input type="text" name="assignee"><br><br>

    <label>Date limite :</label><br>
    <input type="date" name="due_date"><br><br>

    <label>Statut :</label><br>
    <select name="status">
        <option value="todo">À faire</option>
        <option value="in_progress">En cours</option>
        <option value="done">Terminé</option>
    </select><br><br>

    <button type="submit">Créer la tâche</button>
</form>
@endsection
