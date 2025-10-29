@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Modifier le Sprint : {{ $sprint->name }}</h1>

    <form action="{{ route('sprints.update', $sprint->id_sprint) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold mb-1">Nom du Sprint</label>
            <input type="text" name="name" value="{{ old('name', $sprint->name) }}" class="w-full border px-3 py-2 rounded">
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Date de début</label>
            <input type="date" name="start_date" value="{{ old('start_date', $sprint->start_date) }}" class="w-full border px-3 py-2 rounded">
            @error('start_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Date de fin</label>
            <input type="date" name="end_date" value="{{ old('end_date', $sprint->end_date) }}" class="w-full border px-3 py-2 rounded">
            @error('end_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('projects.roadmap', $sprint->epic->project_id) }}" class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">⬅ Retour</a>
            <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
