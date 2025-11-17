@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-16 rounded-xl shadow-2xl px-10 py-8" style="background: rgba(20, 18, 50, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(177, 185, 234, 0.2);">
    <h1 class="text-3xl font-extrabold mb-6 text-center" style="color: #b1b9ea; font-family: 'Poppins', sans-serif;">Créer un projet</h1>
    <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block text-base font-semibold mb-2" style="color: #d5dff5;">Nom du projet :</label>
            <input type="text" name="name" required
                   class="w-full px-4 py-2 rounded-lg border backdrop-filter transition focus:outline-none focus:ring-2 text-lg shadow"
                   style="background: rgba(177, 185, 234, 0.1); border-color: rgba(177, 185, 234, 0.3); color: #b1b9ea;"
                   placeholder="Nom du projet"
            />
        </div>
        <button type="submit"
            class="w-full font-bold py-3 rounded-lg shadow-lg transition text-lg flex items-center justify-center gap-2 cursor-pointer"
            style="background: #5b6cb2; color: white; border: none;"
            onmouseover="this.style.background='#43519e';"
            onmouseout="this.style.background='#5b6cb2';">
            Créer le projet
        </button>
    </form>
</div>
<div class="w-full max-w-[1200px] mx-auto flex justify-start mt-10 px-4">
    <button type="button"
        onclick="window.history.back()"
        class="font-semibold py-2 px-5 rounded-lg shadow transition active:scale-95 active:shadow-inner flex items-center gap-2 cursor-pointer"
        style="background: rgba(177, 185, 234, 0.2); color: #b1b9ea; border: 1px solid rgba(177, 185, 234, 0.5);"
        onmouseover="this.style.background='rgba(177, 185, 234, 0.3)'; this.style.borderColor='#b1b9ea';"
        onmouseout="this.style.background='rgba(177, 185, 234, 0.2)'; this.style.borderColor='rgba(177, 185, 234, 0.5)';">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" stroke="currentColor"/>
        </svg>
        Retour
    </button>
</div>
@endsection
