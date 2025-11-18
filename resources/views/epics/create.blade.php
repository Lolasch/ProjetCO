@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-[#232332] border border-[#373755] rounded-xl shadow-md p-7 mt-6">
    <h1 class="text-2xl font-bold text-center mb-6 text-[#BBBCE1]">
        Créer un Epic pour le sprint&nbsp;: <span class="text-[#8EE6D7]">{{ $sprint->name }}</span>
    </h1>

    <form action="{{ route('epics.store', $sprint->id_sprint) }}" method="POST">
        @csrf

        <label for="name" class="block font-semibold mb-2 text-[#BBBCE1]">Nom de l'Epic :</label>
        <input
            type="text"
            name="name"
            id="name"
            required
            class="w-full mb-5 bg-[#181826] border border-[#373755] rounded-xl p-2 text-[#E6E8F5] focus:ring-2 focus:ring-[#6D80EF] focus:outline-none"
        >

        <div class="flex justify-between items-center">
            <a href="{{ route('projects.roadmap', $sprint->project_id) }}"
               class="bg-[#C9A8FD] text-[#232332] px-4 py-2 rounded-xl font-semibold hover:bg-[#E0BFFD] transition">
               Annuler
            </a>
            <button
                type="submit"
                class="bg-[#8EE6D7] text-[#232332] px-5 py-2 rounded-xl font-semibold hover:bg-[#A4F2E3] transition"
            >
                Créer l'Epic
            </button>
        </div>
    </form>
</div>
@endsection
