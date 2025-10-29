@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Modifier l'Epic : {{ $epic->name }}</h1>

    <form action="{{ route('epics.update', $epic->id_epic) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold mb-1">Nom de l'Epic</label>
            <input type="text" name="name" value="{{ old('name', $epic->name) }}" class="w-full border px-3 py-2 rounded">
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" class="w-full border px-3 py-2 rounded">{{ old('description', $epic->description) }}</textarea>
            @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('projects.roadmap', $epic->project_id) }}" class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">⬅ Retour</a>
            <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
