@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-md rounded p-6 mt-6">
    <h2 class="text-xl font-semibold mb-4">Créer une nouvelle release pour {{ $project->name }}</h2>

    <form action="{{ route('releases.store', $project->id_project) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="name" class="block font-medium">Nom de la release :</label>
            <input type="text" id="name" name="name" class="border rounded w-full p-2" required>
        </div>

        <div class="mb-4">
            <label for="release_date" class="block font-medium">Date de la release :</label>
            <input type="date" id="release_date" name="release_date" class="border rounded w-full p-2" required>
        </div>

        <div class="mb-4">
            <label for="color" class="block font-medium">Couleur (facultatif) :</label>
            <input type="color" id="color" name="color" class="border rounded w-16 h-8">
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Enregistrer
        </button>

        <a href="{{ route('projects.roadmap', $project->id_project) }}" class="ml-2 text-gray-600 hover:underline">Annuler</a>
    </form>
</div>
@endsection
