@extends('layouts.app')

@section('content')
<div class="w-full px-4 pb-10">
    <div class="relative flex items-center mb-10">
        <h1 class="text-5xl font-black mb-0 leading-tight" style="color:#b1b9ea;">Mes projets</h1>
        <a href="{{ route('projects.create') }}"
           class="absolute right-0 top-1 bg-[#5b6cb2]/90 hover:bg-[#39356d] active:scale-95 active:shadow-inner text-white px-5 py-2 rounded-2xl shadow-lg transition text-base font-bold flex items-center"
           style="box-shadow: 0 4px 24px 0 rgba(91, 108, 178, 0.18);">
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
            'manager'   => 'text-[#b1b9ea]',
            'associate' => 'text-[#8ae5ed]',
            'client'    => 'text-[#f1b5d8]',
        ];
        $barColors = [
            'manager'   => 'bg-[#5b6cb2]',
            'associate' => 'bg-[#60b8c9]',
            'client'    => 'bg-[#f2aad4]',
        ];
        $cardBg = [
            'manager'   => 'bg-[#222133]/90 backdrop-blur',
            'associate' => 'bg-[#14353e]/90 backdrop-blur',
            'client'    => 'bg-[#352239]/90 backdrop-blur',
        ];
    @endphp

    <h2 class="text-2xl font-bold mb-6 mt-8" style="color:#b1b9ea;">Projets où je suis chef de projet</h2>
    @if(count($projectsChef) === 0)
        <p class="text-gray-400 text-center mb-10 text-lg">Aucun projet où vous êtes chef de projet.</p>
    @else
        <div id="chef-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 px-2 mb-12">
        @foreach($projectsChef as $proj)
            @php
                $roleKey = 'manager';
                $sprints = $proj->sprints()->with(['epics.tasks', 'tasks'])->get();
                $tasks = collect();
                foreach ($sprints as $sprint) {
                    foreach ($sprint->epics as $epic) { $tasks = $tasks->merge($epic->tasks); }
                    $tasks = $tasks->merge($sprint->tasks);
                }
                $total = $tasks->count();
                $done = $tasks->where('status', 'done')->count();
                $progress = $total > 0 ? round($done * 100 / $total) : 0;
            @endphp

            <div class="rounded-3xl shadow-2xl px-10 py-7 flex flex-col items-center max-w-xl w-full mx-auto project-card {{ $cardBg[$roleKey] ?? 'bg-[#222133]/80 backdrop-blur' }} ring-1 ring-[#8586ae]/50 border border-[#2d2242]/50">
                <h3 class="text-2xl font-black mb-2 {{ $roleColors[$roleKey] ?? 'text-[#b1b9ea]' }} text-center w-full truncate">{{ $proj->name }}</h3>
                <span class="block mt-2 mb-4 {{ $roleColors[$roleKey] ?? 'text-[#b1b9ea]' }} font-bold text-base">Chef de projet</span>
                <div class="w-full h-2 bg-[#39356d]/60 rounded-full mb-2 mt-2">
                    <div class="h-2 rounded-full {{ $barColors[$roleKey] ?? 'bg-[#b1b9ea]' }}" style="width: {{ $progress }}%"></div>
                </div>
                <p class="text-xs {{ $roleColors[$roleKey] ?? 'text-[#b1b9ea]' }} mb-2 text-right w-full">{{ $progress }}% accompli</p>
                <div class="flex w-full justify-center gap-5 mb-2">
                    <a href="{{ route('projects.kanban', $proj->id_project) }}"
                        class="bg-[#bdd3ee] text-[#1e233e] py-3 px-6 rounded-xl shadow-lg hover:bg-[#b1b9ea] transition font-semibold text-center">
                        Kanban
                    </a>
                    <a href="{{ route('projects.roadmap', $proj->id_project) }}"
                        class="bg-[#a3c9c3] text-[#14403b] py-3 px-6 rounded-xl shadow-lg hover:bg-[#7be3b7] transition font-semibold text-center">
                        Roadmap
                    </a>
                </div>
                <div class="flex flex-row items-center gap-4 mt-2 px-2" style="width:max-content;">
                    <a href="{{ route('projects.reporting', $proj->id_project) }}"
                        class="bg-[#e7b8e8] text-[#371646] px-4 py-2 rounded-lg text-xs font-semibold shadow-md hover:bg-[#f9c6f4] transition flex items-center">
                        Vue d’ensemble
                    </a>
                    <button onclick="openDeleteModal('{{ $proj->id_project }}')" class="p-2 rounded-full hover:bg-[#fdcfd3]/80 transition ml-1" title="Supprimer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" class="w-7 h-7">
                            <rect x="5" y="7" width="14" height="12" rx="2.5" stroke="#F7615F" stroke-width="2" fill="#fdcfd3"/>
                            <path d="M10 11v5M14 11v5" stroke="#F7615F" stroke-width="2" stroke-linecap="round"/>
                            <rect x="8" y="4" width="8" height="3" rx="1.2" fill="#fdcfd3" stroke="#F7615F" stroke-width="1"/>
                            <path d="M3 6h18" stroke="#F7615F" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <a href="{{ route('projects.edit', $proj->id_project) }}"
                        class="p-2 rounded-full hover:bg-[#fff5c1] transition ml-1" title="Modifier">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#fff5c1" viewBox="0 0 24 24" class="w-10 h-10" stroke="#cabf53" stroke-width="1.5">
                            <path d="M16.2 7.8l-1.7-1.7a1 1 0 0 0-1.4 0l-5.7 5.7a1 1 0 0 0-.26.47l-.5 2.2a.3.3 0 0 0 .37.37l2.2-.5a1 1 0 0 0 .47-.26l5.7-5.7a1 1 0 0 0 0-1.4z"/>
                            <rect x="5" y="16" width="8" height="2" rx="1"/>
                        </svg>
                    </a>
                </div>
            </div>
        @endforeach
        </div>
    @endif

    <h2 class="text-2xl font-bold mb-6 mt-14" style="color:#8ae5ed;">Projets auxquels je suis associé(e)</h2>
    @if(count($projectsAssoc) === 0)
        <p class="text-gray-400 text-center mb-10 text-lg">Aucun projet où vous êtes collaborateur ou client.</p>
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
                    foreach ($sprint->epics as $epic) { $tasks = $tasks->merge($epic->tasks); }
                    $tasks = $tasks->merge($sprint->tasks);
                }
                $total = $tasks->count();
                $done = $tasks->where('status', 'done')->count();
                $progress = $total > 0 ? round($done * 100 / $total) : 0;
            @endphp
            <div class="rounded-3xl shadow-2xl px-10 py-7 flex flex-col items-center max-w-xl w-full mx-auto project-card {{ $cardBg[$roleKey] ?? 'bg-[#222133]/80 backdrop-blur' }} ring-1 ring-[#60b8c9]/40 border border-[#14353e]/40">
                <h3 class="text-2xl font-black mb-2 text-[#8ae5ed] text-center w-full truncate">{{ $proj->name }}</h3>
                <span class="block mt-2 mb-4 {{ $roleColors[$roleKey] ?? 'text-[#8ae5ed]' }} font-bold text-base">{{ $roleLabel }}</span>
                <div class="w-full h-2 bg-[#2c495d]/50 rounded-full mb-2 mt-2">
                    <div class="h-2 rounded-full {{ $barColors[$roleKey] ?? 'bg-[#b1b9ea]' }}" style="width: {{ $progress }}%"></div>
                </div>
                <p class="text-xs {{ $roleColors[$roleKey] ?? 'text-[#8ae5ed]' }} mb-2 text-right w-full">{{ $progress }}% accompli</p>
                <div class="flex w-full space-x-4 mb-3 justify-center">
                    <a href="{{ route('projects.kanban', $proj->id_project) }}"
                        class="flex-1 bg-[#bdd3ee] text-[#1e233e] py-3 rounded-xl shadow-lg hover:bg-[#b1b9ea] transition text-base font-semibold text-center">
                        Kanban
                    </a>
                    <a href="{{ route('projects.roadmap', $proj->id_project) }}"
                        class="flex-1 bg-[#70b5ad] text-[#203d3a] py-3 rounded-xl shadow-lg hover:bg-[#5cdabd] transition text-base font-semibold text-center">
                        Roadmap
                    </a>
                </div>
                <a href="{{ route('projects.reporting', $proj->id_project) }}"
                    class="mx-auto w-fit mt-1 bg-[#e7b8e8] text-[#371646] px-4 py-2 rounded-lg text-sm font-semibold text-center shadow-md hover:bg-[#f9c6f4] active:scale-95 active:shadow-inner transition flex items-center gap-2 justify-center">
                    Vue d’ensemble
                </a>
            </div>
        @endforeach
        </div>
    @endif
</div>

<div id="delete-modal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-40 hidden">
    <div class="bg-[#161426] rounded-2xl p-7 min-w-[320px] text-center ring-2 ring-[#b1b9ea]/50 shadow-2xl">
        <h3 class="font-bold text-xl mb-4" style="color:#b1b9ea;">Supprimer le projet</h3>
        <p class="text-[#b1b9ea]/70 mb-6">Cette action est irréversible. Confirmer la suppression&nbsp;?</p>
        <div class="flex justify-center gap-5">
            <form id="delete-form" action="" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2 font-semibold rounded-xl bg-[#F7615F] text-white shadow-md hover:bg-[#d64545]">Oui</button>
            </form>
            <button type="button" onclick="closeDeleteModal()" class="px-5 py-2 font-semibold rounded-xl bg-[#b1b9ea]/80 text-[#222133] shadow-md hover:bg-[#efeaff]">Non</button>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(id) {
        document.getElementById('delete-modal').classList.remove('hidden');
        document.getElementById('delete-form').action = '/projects/' + id;
    }
    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
</script>
@endsection
