@extends('layouts.app')

@section('content')
@php
    $currentMember = $project->members->firstWhere('id_user', auth()->id());
    $currentRole = $currentMember ? $currentMember->pivot->role : null;
    $clientCode = 'CLIENT-' . strtoupper(substr(hash('crc32', $project->id_project . 'SECRET_SALT'),0,8));
    $clientUrl = route('projects.sharedView', ['project'=>$project->id_project, 'code'=>$clientCode]);
@endphp

<div class="max-w-5xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6 text-center text-[#1c2352]">Reporting du projet {{ $project->name }}</h1>

    <!-- SECTION PRINCIPALE stats à gauche, kanban/roadmap à droite -->
    <div class="flex flex-col md:flex-row gap-6 mb-8">
        <!-- Bloc Stats (gauche) -->
        <div class="w-full md:w-1/3 flex flex-col gap-4">
            <div class="bg-[#fde047] rounded-xl p-4 flex flex-col items-center shadow">
                <div class="font-bold text-[#26428b] mb-2">À faire</div>
                <span class="text-3xl font-semibold text-[#1c2352]">{{ $count_todo }}</span>
            </div>
            <div class="bg-[#818cf8] rounded-xl p-4 flex flex-col items-center shadow">
                <div class="font-bold text-[#26428b] mb-2">En cours</div>
                <span class="text-3xl font-semibold text-[#1c2352]">{{ $count_progress }}</span>
            </div>
            <div class="bg-[#22c55e] rounded-xl p-4 flex flex-col items-center shadow">
                <div class="font-bold text-[#26428b] mb-2">Terminées</div>
                <span class="text-3xl font-semibold text-[#1c2352]">{{ $count_done }}</span>
            </div>
            <div class="bg-[#bfcbe1] rounded-xl p-4 flex flex-col items-center shadow">
                <div class="font-bold text-[#26428b] mb-2">Total</div>
                <span class="text-3xl font-semibold text-[#1c2352]">{{ $count_total }}</span>
            </div>
        </div>
        <!-- Kanban et Roadmap (droite) -->
        <div class="w-full md:w-2/3 flex flex-col gap-4">
            <a href="{{ route('projects.kanban', $project->id_project) }}" class="flex-1 bg-gradient-to-r from-[#bfcbe1] via-[#cfd4f7] to-[#5b6cb2] rounded-xl p-4 text-center shadow-md hover:scale-[1.03] transition">
                <div class="flex flex-col items-center gap-2">
                    <span class="text-[#26428b] text-lg font-semibold">Kanban</span>
                    <span class="material-symbols-outlined text-4xl text-[#26428b]">view_column</span>
                    <span class="text-xs text-[#26428b]">Visualiser et suivre les tâches du sprint</span>
                </div>
            </a>
            <a href="{{ route('projects.roadmap', $project->id_project) }}" class="flex-1 bg-gradient-to-r from-[#e4e3ef] via-[#d7dae7] to-[#bfcbe1] rounded-xl p-4 text-center shadow-md hover:scale-[1.03] transition">
                <div class="flex flex-col items-center gap-2">
                    <span class="text-[#1c2352] text-lg font-semibold">Roadmap</span>
                    <span class="material-symbols-outlined text-4xl text-[#1c2352]">timeline</span>
                    <span class="text-xs text-[#1c2352]">Voir tous les sprints, epics et releases</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Bloc membres et ajout -->
    <div class="bg-[#e4e3ef] rounded-2xl p-6 mb-8">
        <div class="font-bold mb-2 text-[#26428b]">Membres du projet :</div>
        <ul class="space-y-1">
        @foreach($members as $m)
            <li>
                {{ $m->name }}
                <span class="text-xs px-2 py-1 rounded-2xl
                    @if($m->pivot->role === 'manager') bg-[#4b68b7] text-white
                    @elseif($m->pivot->role === 'associate') bg-[#5b6cb2] text-white
                    @else bg-[#bfcbe1] text-[#153959]
                    @endif
                ">
                    {{ ucfirst($m->pivot->role) }}
                </span>
            </li>
        @endforeach
        </ul>
        @if($currentRole === 'manager')
        <form action="{{ route('projects.addMember', $project->id_project) }}" method="POST" class="mt-4 flex gap-2 items-center">
            @csrf
            <input type="text" id="user_search" class="rounded px-2 py-1 border border-gray-300 focus:outline-none" placeholder="Tapez un email ou un nom...">
            <input type="hidden" name="user_id" id="user_id_selected">
            <select name="role" class="rounded px-2 py-1 border border-gray-300 focus:outline-none">
                <option value="associate">Associé</option>
                <option value="client">Client</option>
            </select>
            <button type="submit" class="bg-[#5b6cb2] text-white px-4 py-2 rounded hover:bg-[#43519e]">Ajouter</button>
        </form>
        @endif
    </div>

    <!-- Lien client -->
    <div class="mb-8">
        <div class="bg-[#bfcbe1] rounded-2xl px-6 py-4 flex flex-col items-center">
            <div class="font-bold text-[#26428b] mb-2">Accès client externe (lecture seule) :</div>
            <span class="text-sm text-[#1c2352] break-all">
                Lien à donner au client :
                <span class="font-mono bg-white px-2 py-1 rounded" id="clientUrl">{{ $clientUrl }}</span>
                <button onclick="navigator.clipboard.writeText('{{ $clientUrl }}')" class="ml-2 px-2 py-1 bg-[#4b68b7] text-white rounded">Copier</button>
            </span>
        </div>
    </div>

    <!-- GRAPHES -->
    <div class="mb-12 grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Diagramme en secteurs -->
        <div class="bg-[#e4e3ef] rounded-2xl px-6 py-4 flex flex-col items-center">
            <h2 class="font-bold mb-3 text-[#1c2352]">Diagramme des statuts</h2>
            <canvas id="kanbanPie" width="240" height="240"></canvas>
        </div>
        <!-- Courbe d’avancement -->
        <div class="bg-[#e4e3ef] rounded-2xl px-6 py-4 flex flex-col items-center">
            <h2 class="font-bold mb-3 text-[#1c2352]">Courbe d’avancement</h2>
            <canvas id="progressLine" width="300" height="240"></canvas>
        </div>
    </div>

    <!-- Bouton retour -->
    <div class="flex justify-center">
        <a href="{{ route('dashboard') }}" class="bg-gray-300 px-4 py-2 rounded text-gray-700 hover:bg-gray-400">Retour au dashboard</a>
    </div>
</div>

<!-- JQUERY & AUTOCOMP -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script>
$(function() {
    $("#user_search").autocomplete({
        minLength: 2,
        source: function(request, response) {
            $.getJSON("{{ route('users.searchByEmail') }}", {q: request.term}, function(data) {
                response($.map(data, function(item) {
                    return {
                        label: item.name + " (" + item.email + ")",
                        value: item.email,
                        id: item.id_user
                    };
                }));
            });
        },
        select: function(event, ui) {
            $("#user_search").val(ui.item.label);
            $("#user_id_selected").val(ui.item.id);
            return false;
        }
    });
});
</script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const pieCtx = document.getElementById('kanbanPie');
if(pieCtx) {
  new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels: ['À faire', 'En cours', 'Terminées'],
      datasets: [{
        label: 'Statut des tâches',
        data: [{{ $count_todo }}, {{ $count_progress }}, {{ $count_done }}],
        backgroundColor: ['#fde047','#818cf8','#22c55e'],
        borderWidth: 2,
        borderColor: '#fff'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
          labels: {
            color: '#1c2352',
            font: { size: 16 }
          }
        }
      }
    }
  });
}
// COURBE d'avancement - adapte tes variables PHP au besoin
const lineCtx = document.getElementById('progressLine');
@if(isset($progress_dates) && isset($progress_values))
if(lineCtx) {
  new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: {!! json_encode($progress_dates) !!},
      datasets: [{
        label: 'Tâches terminées',
        data: {!! json_encode($progress_values) !!},
        backgroundColor: '#818cf8',
        borderColor: '#26428b',
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
          labels: {
            color: '#1c2352',
            font: { size: 14 }
          }
        }
      },
      scales: {
        x: { grid: { color: '#bfcbe1' }, ticks: { color: '#1c2352' } },
        y: { grid: { color: '#bfcbe1' }, ticks: { color: '#1c2352' } }
      }
    }
  });
}
@endif
</script>

<!-- Pour icône google fonts -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<style>
.material-symbols-outlined {
  font-variation-settings:
    'FILL' 0,
    'wght' 400,
    'GRAD' 0,
    'opsz' 48
}
</style>
@endsection
