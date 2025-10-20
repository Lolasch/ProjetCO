@extends('layouts.app')

@section('content')
<h1>Modifier le projet</h1>

<form action="{{ route('projects.update', $project->id_project) }}" method="POST">
    @csrf
    @method('PUT')
    <label>Nom du projet :</label>
    <input type="text" name="name" value="{{ $project->name }}" required>
    <button type="submit">Mettre à jour</button>
</form>
@endsection
