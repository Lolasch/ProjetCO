@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-16 bg-white rounded-xl shadow-lg px-10 py-8">
    <h1 class="text-3xl font-extrabold text-[#1c2352] mb-6 text-center">Créer un projet</h1>
    <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block text-base font-semibold text-[#153959] mb-2">Nom du projet :</label>
            <input type="text" name="name" required
                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#26428b] text-lg shadow transition" />
        </div>
        <button type="submit"
            class="w-full bg-[#ffc8dd] hover:bg-[#e295b6] active:scale-95 active:shadow-inner text-white font-bold py-3 rounded-xl shadow-lg transition text-lg flex items-center justify-center gap-2">
            Créer le projet
        </button>
    </form>
</div>
<div class="w-full max-w-[1200px] mx-auto flex justify-start mt-10">
    <button type="button"
        onclick="window.history.back()"
        class="bg-[#e2e9f8] hover:bg-[#c7cfe4] text-[#1c2352] font-semibold py-2 px-5 rounded-xl shadow transition active:scale-95 active:shadow-inner flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Retour
    </button>
</div>
@endsection
