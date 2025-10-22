@extends('layouts.app')

@section('content')
<h1>Créer un sprint pour {{ $project->name }}</h1>

<form action="{{ route('sprints.store', $project) }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Nom du sprint" required>
    <input type="date" name="start_date" required>
    <input type="date" name="end_date" required>
    <button type="submit">Créer Sprint</button>
</form>
@endsection
