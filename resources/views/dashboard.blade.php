@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-4xl font-extrabold text-[#1c2352]">Mes projets</h1>
    <a href="{{ route('projects.create') }}"
       class="bg-[#26428b] hover:bg-[#1c2352] text-white px-6 py-3 rounded-xl shadow transition text-lg font-bold flex items-center">
        Créer un projet
        <svg class="w-6 h-6 ml-2" fill="none" stroke="#1c2352" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
    </a>
</div>

<!-- Barre de recherche -->
<div class="flex justify-center mb-10">
    <input id="searchbar" type="text" placeholder="Filtrer par projet, rôle ou membre (chef, client, ...)"
        class="rounded-lg border border-gray-300 px-6 py-2 text-lg focus:outline-none shadow w-full max-w-lg" />
</div>

@php
    $projectsChef = [];
    $projectsAssoc = [];
    foreach ($projects as $project) {
        $role = $project->pivot->role;
        if ($role === 'manager') {
            $projectsChef[] = $project;
        } else {
            $projectsAssoc[] = $project;
        }
    }
@endphp

<div class="mb-12">
    <h2 class="text-2xl font-bold mb-6 text-[#1c2352]">Projets où je suis chef de projet</h2>
    @if(count($projectsChef) === 0)
        <p class="text-gray-500 text-center mb-10 text-lg">Aucun projet où vous êtes chef de projet.</p>
    @else
        <div id="chef-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 px-2 mb-12">
        @foreach($projectsChef as $proj)
            <div class="bg-[#e4e3ef] rounded-3xl shadow-lg px-10 py-7 flex flex-col items-center max-w-xl w-full mx-auto project-card"
                data-title="{{ strtolower($proj->name) }}" data-role="chef de projet">
                <h3 class="text-2xl font-bold mb-2 text-[#1c2352] text-center w-full truncate">{{ $proj->name }}</h3>
                <div class="mb-3 flex items-center">
                    <span class="text-[15px] font-bold px-5 py-2 rounded-2xl text-white bg-[#4b68b7] shadow">
                        Chef de projet
                    </span>
                </div>
                <div class="flex w-full space-x-4 mb-3 justify-center">
                    <a href="{{ route('projects.kanban', $proj->id_project) }}"
                    class="flex-1 bg-[#5b6cb2] text-white py-3 rounded-xl hover:bg-[#43519e] text-base font-semibold text-center shadow-md">Kanban</a>
                    <a href="{{ route('projects.roadmap', $proj->id_project) }}"
                    class="flex-1 bg-[#8ed2f6] text-[#153959] py-3 rounded-xl hover:bg-[#6bbfde] text-base font-semibold text-center shadow-md">Roadmap</a>
                </div>
                <a href="{{ route('projects.reporting', $proj->id_project) }}"
                class="w-2/4 mx-auto mt-1 bg-fuchsia-500 text-white py-3 rounded-xl hover:bg-fuchsia-700 text-base font-semibold text-center shadow-md block">
                    Visualiser
                </a>
            </div>
        @endforeach
        </div>
    @endif
</div>

<div>
    <h2 class="text-2xl font-bold mb-6 text-[#1c2352]">Projets auxquels je suis associé(e)</h2>
    @if(count($projectsAssoc) === 0)
        <p class="text-gray-500 text-center mb-10 text-lg">Aucun projet où vous êtes collaborateur ou client.</p>
    @else
        <div id="assoc-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 px-2 mb-10">
        @foreach($projectsAssoc as $proj)
            @php
                $role = $proj->pivot->role;
                $roleLabel = $role === 'employee' ? 'Collaborateur' : ($role === 'client' ? 'Client' : ucfirst($role));
                $roleColor = $role === 'employee'
                    ? 'bg-[#5b6cb2] text-white'
                    : 'bg-[#bfcbe1] text-[#153959]';
            @endphp
            <div class="bg-[#e4e3ef] rounded-3xl shadow-lg px-10 py-7 flex flex-col items-center max-w-xl w-full mx-auto project-card"
                data-title="{{ strtolower($proj->name) }}" data-role="{{ strtolower($roleLabel) }}">
                <h3 class="text-2xl font-bold mb-2 text-[#1c2352] text-center w-full truncate">{{ $proj->name }}</h3>
                <div class="mb-3 flex items-center">
                    <span class="text-[15px] font-bold px-5 py-2 rounded-2xl {{ $roleColor }} shadow">
                        {{ $roleLabel }}
                    </span>
                </div>
                <div class="flex w-full space-x-4 mb-3 justify-center">
                    <a href="{{ route('projects.kanban', $proj->id_project) }}"
                    class="flex-1 bg-[#5b6cb2] text-white py-3 rounded-xl hover:bg-[#43519e] text-base font-semibold text-center shadow-md">Kanban</a>
                    <a href="{{ route('projects.roadmap', $proj->id_project) }}"
                    class="flex-1 bg-[#8ed2f6] text-[#153959] py-3 rounded-xl hover:bg-[#6bbfde] text-base font-semibold text-center shadow-md">Roadmap</a>
                </div>
                <a href="{{ route('projects.reporting', $proj->id_project) }}"
                class="w-2/4 mx-auto mt-1 bg-fuchsia-500 text-white py-3 rounded-xl hover:bg-fuchsia-700 text-base font-semibold text-center shadow-md block">
                    Visualiser
                </a>
            </div>
        @endforeach
        </div>
    @endif
</div>

<script>
document.getElementById('searchbar').addEventListener('input', function(e) {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('.project-card').forEach(card => {
        const title = card.dataset.title;
        const role = card.dataset.role;
        if(title.includes(q) || role.includes(q) || q === '') {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>
@endsection
