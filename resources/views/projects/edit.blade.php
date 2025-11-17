@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-16 rounded-xl shadow-2xl p-8" style="background: rgba(20, 18, 50, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(177, 185, 234, 0.2);">
    <h1 class="text-3xl font-extrabold mb-6 text-center" style="color: #b1b9ea; font-family: 'Poppins', sans-serif;">Modifier le projet</h1>

    <form action="{{ route('projects.update', $project->id_project) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-base font-semibold mb-2" style="color: #d5dff5;">Nom du projet :</label>
            <input
                type="text"
                name="name"
                value="{{ $project->name }}"
                required
                class="w-full px-4 py-2 rounded-lg border backdrop-filter transition focus:outline-none focus:ring-2 text-lg shadow"
                style="background: rgba(177, 185, 234, 0.1); border-color: rgba(177, 185, 234, 0.3); color: #b1b9ea;"
                placeholder="Nom du projet"
            >
        </div>

        <button
            type="submit"
            class="w-full font-bold py-3 rounded-lg shadow-lg transition text-lg cursor-pointer"
            style="background: #5b6cb2; color: white; border: none;"
            onmouseover="this.style.background='#43519e';"
            onmouseout="this.style.background='#5b6cb2';">
            Mettre à jour
        </button>
    </form>
</div>

<div class="w-full max-w-md mx-auto flex justify-start mt-6 px-4">
    <a href="{{ url()->previous() }}"
       class="inline-block px-6 py-2 rounded-lg font-semibold shadow transition text-base cursor-pointer"
       style="background: rgba(177, 185, 234, 0.2); color: #b1b9ea; border: 1px solid rgba(177, 185, 234, 0.5);"
       onmouseover="this.style.background='rgba(177, 185, 234, 0.3)'; this.style.borderColor='#b1b9ea';"
       onmouseout="this.style.background='rgba(177, 185, 234, 0.2)'; this.style.borderColor='rgba(177, 185, 234, 0.5)';">
        ← Retour
    </a>
</div>
@endsection
