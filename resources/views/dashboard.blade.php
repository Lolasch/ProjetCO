@extends('layouts.app')

@section('content')
<div class="px-8 py-6">
    <h1 class="text-3xl font-bold mb-6 text-center">Tableau de bord</h1>

    <div class="text-center mb-6">
        <a href="{{ route('projects.create') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded-xl shadow hover:bg-blue-700 transition">
            ➕ Créer un projet
        </a>
    </div>

    @php
        $roles = ['manager' => [], 'employee' => [], 'client' => []];
    @endphp

    @foreach($projects as $project)
        @php
            $role = $project->pivot->role;
            $roles[$role][] = $project;
        @endphp
    @endforeach

    <!-- Conteneur 3 colonnes -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($roles as $role => $projList)
            <div class="bg-white rounded-2xl shadow-lg p-4 border">
                <h2 class="text-2xl font-semibold mb-4 text-center capitalize">{{ $role }}</h2>

                @if(count($projList) === 0)
                    <p class="text-gray-500 text-center">Aucun projet.</p>
                @else
                    <div class="space-y-4">
                        @foreach($projList as $proj)
                            <div class="bg-gray-50 rounded-xl shadow p-4 hover:shadow-md transition">
                                <img src="https://via.placeholder.com/400x150.png?text=Image+du+projet"
                                     alt="Image du projet"
                                     class="w-full h-32 object-cover rounded-lg mb-3">

                                <h3 class="text-lg font-bold mb-2">{{ $proj->name }}</h3>

                                <div class="flex justify-between mb-3">
                                    <a href="#" class="bg-indigo-500 text-white px-3 py-1 rounded-lg hover:bg-indigo-600 text-sm">Kanban</a>
                                    <a href="#" class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 text-sm">Roadmap</a>
                                </div>

                                <div class="text-sm text-gray-600 mb-2">
                                    <p><strong>Début :</strong> {{ $proj->start_date ?? 'Non défini' }}</p>
                                    <p><strong>Fin :</strong> {{ $proj->end_date ?? 'Non définie' }}</p>
                                </div>

                                @if($role === 'manager')
                                    <div class="flex justify-between mt-3">
                                        <a href="{{ route('projects.edit', $proj->id_project) }}"
                                           class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 text-sm">
                                            Modifier
                                        </a>

                                        <form action="{{ route('projects.destroy', $proj->id_project) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 text-sm">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
