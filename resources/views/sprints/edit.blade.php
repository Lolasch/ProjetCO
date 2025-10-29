@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4 text-center">Modifier le Sprint</h2>

    <form action="{{ route('sprints.update', $sprint->id_sprint) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block font-semibold">Nom du sprint :</label>
            <input type="text" name="name" id="name" class="w-full border rounded p-2" value="{{ $sprint->name }}" required>
        </div>

        <div class="mb-4">
            <label for="start_date" class="block font-semibold">Date de début :</label>
            <input type="date" name="start_date" id="start_date" class="w-full border rounded p-2" value="{{ $sprint->start_date }}" required>
        </div>

        <div class="mb-4">
            <label for="end_date" class="block font-semibold">Date de fin :</label>
            <input type="date" name="end_date" id="end_date" class="w-full border rounded p-2" value="{{ $sprint->end_date }}" required>
        </div>

        <div class="mb-4">
            <label for="color" class="block font-semibold">Couleur du sprint :</label>
            <input type="color" name="color" id="color" class="w-16 h-10 border rounded" value="{{ $sprint->color }}">
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('projects.roadmap', $sprint->project_id) }}"
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
               ← Retour
            </a>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Sauvegarder
            </button>
        </div>
    </form>
</div>
@endsection
