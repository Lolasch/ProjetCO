@extends('layouts.app')

@section('content')
<h1>Créer un sprint pour le projet : {{ $project->name }}</h1>

<form action="{{ route('sprints.store', $project->id_project) }}" method="POST">
    @csrf
    <label>Nom du sprint :</label><br>
    <input type="text" name="name" required><br><br>

    <label>Date de début :</label><br>
    <input type="date" name="start_date" required><br><br>

    <label>Date de fin :</label><br>
    <input type="date" name="end_date"><br><br>

    <button type="submit">Créer le sprint</button>
</form>
@endsection
