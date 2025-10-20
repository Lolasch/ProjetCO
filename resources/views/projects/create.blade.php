@extends('layouts.app')

@section('content')
<h1>Créer un projet</h1>

<form action="{{ route('projects.store') }}" method="POST">
    @csrf
    <label>Nom du projet :</label>
    <input type="text" name="name" required>
    <button type="submit">Créer</button>
</form>
@endsection
