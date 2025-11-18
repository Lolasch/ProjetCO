@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-[#232332] p-6 rounded-xl shadow-md border border-[#373755]">
    <h2 class="text-2xl font-bold mb-4 text-center text-[#BBBCE1]">Modifier le Sprint</h2>

    <form action="{{ route('sprints.update', $sprint->id_sprint) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block font-semibold text-[#BBBCE1]">Nom du sprint :</label>
            <input type="text" name="name" id="name"
                   class="w-full bg-[#181826] border border-[#373755] rounded-xl p-2 text-[#E6E8F5] focus:outline-none focus:ring-2 focus:ring-[#6D80EF]"
                   value="{{ $sprint->name }}" required>
        </div>

        <div class="mb-4">
            <label for="start_date" class="block font-semibold text-[#BBBCE1]">Date de début :</label>
            <input type="date" name="start_date" id="start_date"
                   class="w-full bg-[#181826] border border-[#373755] rounded-xl p-2 text-[#E6E8F5] focus:outline-none focus:ring-2 focus:ring-[#6D80EF]"
                   value="{{ $sprint->start_date }}" required>
        </div>

        <div class="mb-4">
            <label for="end_date" class="block font-semibold text-[#BBBCE1]">Date de fin :</label>
            <input type="date" name="end_date" id="end_date"
                   class="w-full bg-[#181826] border border-[#373755] rounded-xl p-2 text-[#E6E8F5] focus:outline-none focus:ring-2 focus:ring-[#6D80EF]"
                   value="{{ $sprint->end_date }}" required>
        </div>

        <div class="mb-4">
            <label for="color" class="block font-semibold text-[#BBBCE1]">Couleur du sprint :</label>
            <input type="color" name="color" id="color"
                   class="w-16 h-10 bg-[#181826] border border-[#373755] rounded-xl"
                   value="{{ $sprint->color }}">
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('projects.roadmap', $sprint->project_id) }}"
               class="bg-[#C9A8FD] text-[#232332] px-4 py-2 rounded-xl hover:bg-[#E0BFFD] transition">
            Annuler
            </a>
            <button type="submit"
                    class="bg-[#8EE6D7] text-[#232332] px-4 py-2 rounded-xl hover:bg-[#A4F2E3] transition">
                Sauvegarder
            </button>
        </div>
    </form>
</div>
@endsection
