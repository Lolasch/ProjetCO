@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-[#232332] shadow-md rounded-xl p-6 mt-6 border border-[#373755]">
    <h2 class="text-xl font-semibold mb-4 text-[#BBBCE1] text-center">Créer une nouvelle release pour {{ $project->name }}</h2>

    <form action="{{ route('releases.store', $project->id_project) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="name" class="block font-medium text-[#BBBCE1]">Nom de la release :</label>
            <input type="text" id="name" name="name"
                   class="border border-[#373755] rounded-xl w-full p-2 bg-[#181826] text-[#E6E8F5] focus:ring-2 focus:ring-[#6D80EF] focus:outline-none"
                   required>
        </div>

        <div class="mb-4">
            <label for="release_date" class="block font-medium text-[#BBBCE1]">Date de la release :</label>
            <input type="date" id="release_date" name="release_date"
                   class="border border-[#373755] rounded-xl w-full p-2 bg-[#181826] text-[#E6E8F5] focus:ring-2 focus:ring-[#6D80EF] focus:outline-none"
                   required>
        </div>

        <div class="mb-4">
            <label for="color" class="block font-medium text-[#BBBCE1]">Couleur (facultatif) :</label>
            <input type="color" id="color" name="color"
                   class="border border-[#373755] rounded-xl w-16 h-8 bg-[#181826]">
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('projects.roadmap', $project->id_project) }}"
               class="text-[#BBBCE1] font-semibold hover:underline transition">
                Annuler
            </a>
            <button type="submit" class="bg-[#8EE6D7] hover:bg-[#A4F2E3] text-[#232332] px-4 py-2 rounded-xl font-semibold transition">
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
