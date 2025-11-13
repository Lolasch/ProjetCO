@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-5xl font-black text-[#1c2352] mb-3">Mes projets</h1>
    <a href="{{ route('projects.create') }}"
       class="bg-[#26428b] hover:bg-[#1c2352] active:scale-95 active:shadow-inner text-white px-4 py-2 rounded-xl shadow-lg transition text-base font-bold flex items-center">
        Créer un projet
        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
    </a>
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
    $roleColors = [
        'manager'   => 'text-[#25028a]',
        'associate' => 'text-[#028a8a]',
        'client'    => 'text-[#8a0278]',
    ];
    $barColors = [
        'manager'   => 'bg-[#25028a]',
        'associate' => 'bg-[#028a8a]',
        'client'    => 'bg-[#8a0278]',
    ];
@endphp

<div class="mb-12">
    <h2 class="text-2xl font-bold mb-6 text-[#1c2352]">Projets où je suis chef de projet</h2>
    @if(count($projectsChef) === 0)
        <p class="text-gray-500 text-center mb-10 text-lg">Aucun projet où vous êtes chef de projet.</p>
    @else
        <div id="chef-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 px-2 mb-12">
        @foreach($projectsChef as $proj)
            @php
                $role = $proj->pivot->role;
                $roleLabel = 'Chef de projet';
                $roleKey = 'manager';

                $sprints = $proj->sprints()->with(['epics.tasks', 'tasks'])->get();
                $tasks = collect();
                foreach ($sprints as $sprint) {
                    foreach ($sprint->epics as $epic) {
                        $tasks = $tasks->merge($epic->tasks);
                    }
                    $tasks = $tasks->merge($sprint->tasks);
                }
                $total = $tasks->count();
                $done = $tasks->where('status', 'done')->count();
                $progress = $total > 0 ? round($done * 100 / $total) : 0;
            @endphp
            <div class="bg-[#e2e9f8] rounded-3xl shadow-lg px-10 py-7 flex flex-col items-center max-w-xl w-full mx-auto project-card">
                <h3 class="text-2xl font-black mb-0 text-[#1c2352] text-center w-full truncate">{{ $proj->name }}</h3>
                <span class="block mt-2 mb-4 {{ $roleColors[$roleKey] ?? 'text-blue-400' }} font-bold text-base">{{ $roleLabel }}</span>
                <div class="w-full h-2 bg-gray-200 rounded-full mb-2 mt-2">
                    <div class="h-2 rounded-full {{ $barColors[$roleKey] ?? 'bg-[#96b6e1]' }}" style="width: {{ $progress }}%"></div>
                </div>
                <p class="text-xs {{ $roleColors[$roleKey] ?? 'text-blue-400' }} mb-2 text-right w-full">{{ $progress }}% accompli</p>
                <div class="flex w-full space-x-4 mb-3 justify-center">
                    <a href="{{ route('projects.kanban', $proj->id_project) }}"
                        class="flex-1 bg-[#7287c2] text-white py-3 rounded-xl shadow-lg hover:bg-[#43519e] active:scale-95 active:shadow-inner transition text-base font-semibold text-center">
                        Kanban
                    </a>
                    <a href="{{ route('projects.roadmap', $proj->id_project) }}"
                        class="flex-1 bg-[#a7dbf7] text-[#153959] py-3 rounded-xl shadow-lg hover:bg-[#6bbfde] active:scale-95 active:shadow-inner transition text-base font-semibold text-center">
                        Roadmap
                    </a>
                </div>
                <a href="{{ route('projects.reporting', $proj->id_project) }}"
   class="mx-auto w-fit mt-1 bg-[#ffc8dd] text-white px-4 py-2 rounded-lg text-sm font-semibold text-center shadow-lg hover:bg-[#f1a9cc] active:scale-95 active:shadow-inner transition flex items-center gap-2 justify-center">
    Vue d’ensemble
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
                $roleLabel =
                    ($role === 'employee' || $role === 'associate') ? 'Collaborateur' :
                    ($role === 'client' ? 'Client' : ucfirst($role));
                $roleKey =
                    ($role === 'employee' || $role === 'associate') ? 'associate' :
                    ($role === 'client' ? 'client' : 'manager');

                $sprints = $proj->sprints()->with(['epics.tasks', 'tasks'])->get();
                $tasks = collect();
                foreach ($sprints as $sprint) {
                    foreach ($sprint->epics as $epic) {
                        $tasks = $tasks->merge($epic->tasks);
                    }
                    $tasks = $tasks->merge($sprint->tasks);
                }
                $total = $tasks->count();
                $done = $tasks->where('status', 'done')->count();
                $progress = $total > 0 ? round($done * 100 / $total) : 0;
            @endphp
            <div class="bg-[#e2e9f8] rounded-3xl shadow-lg px-10 py-7 flex flex-col items-center max-w-xl w-full mx-auto project-card">
                <h3 class="text-2xl font-black mb-0 text-[#1c2352] text-center w-full truncate">{{ $proj->name }}</h3>
                <span class="block mt-2 mb-4 {{ $roleColors[$roleKey] ?? 'text-blue-400' }} font-bold text-base">{{ $roleLabel }}</span>
                <div class="w-full h-2 bg-gray-200 rounded-full mb-2 mt-2">
                    <div class="h-2 rounded-full {{ $barColors[$roleKey] ?? 'bg-[#96b6e1]' }}" style="width: {{ $progress }}%"></div>
                </div>
                <p class="text-xs {{ $roleColors[$roleKey] ?? 'text-blue-400' }} mb-2 text-right w-full">{{ $progress }}% accompli</p>
                <div class="flex w-full space-x-4 mb-3 justify-center">
                    <a href="{{ route('projects.kanban', $proj->id_project) }}"
                        class="flex-1 bg-[#7287c2] text-white py-3 rounded-xl shadow-lg hover:bg-[#43519e] active:scale-95 active:shadow-inner transition text-base font-semibold text-center">
                        Kanban
                    </a>
                    <a href="{{ route('projects.roadmap', $proj->id_project) }}"
                        class="flex-1 bg-[#a7dbf7] text-[#153959] py-3 rounded-xl shadow-lg hover:bg-[#6bbfde] active:scale-95 active:shadow-inner transition text-base font-semibold text-center">
                        Roadmap
                    </a>
                </div>
            <a href="{{ route('projects.reporting', $proj->id_project) }}"
   class="mx-auto w-fit mt-1 bg-[#ffc8dd] text-white px-4 py-2 rounded-lg text-sm font-semibold text-center shadow-lg hover:bg-[#f1a9cc] active:scale-95 active:shadow-inner transition flex items-center gap-2 justify-center">
    Vue d’ensemble
</a>

            </div>
        @endforeach
        </div>
    @endif
</div>
@endsection
