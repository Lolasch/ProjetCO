@extends('layouts.app')

@section('content')
<h1>Ajouter un Epic au projet : {{ $project->name }}</h1>

<form action="{{ route('epics.store', $project->id_project) }}" method="POST">
    @csrf
    <label>Nom de l’Epic :</label>
    <input type="text" name="name" required class="border p-2 rounded w-full mb-2">

    <label>Description :</label>
    <textarea name="description" class="border p-2 rounded w-full mb-2"></textarea>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Créer l’Epic</button>
</form>
@endsection
