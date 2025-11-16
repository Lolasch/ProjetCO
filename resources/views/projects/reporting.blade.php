@extends('layouts.app')
@section('content')
@php
    $currentMember = $project->members->firstWhere('id_user', auth()->id());
    $currentRole = $currentMember ? $currentMember->pivot->role : null;
    $clientCode = 'CLIENT-' . strtoupper(substr(hash('crc32', $project->id_project . 'SECRET_SALT'),0,8));
    $clientUrl = route('projects.sharedView', ['project'=>$project->id_project, 'code'=>$clientCode]);
@endphp

<div class="px-6 py-8">
  <div class="max-w-7xl mx-auto flex flex-col gap-4">

    <!-- Ligne 1 : Rapport + Membres projet bien alignés (en bas) -->
    <div class="grid grid-cols-4 gap-6 items-stretch">
      <!-- Bloc rapport des tâches aligné bas -->
      <div class="col-span-1 flex flex-col justify-end">
        <div class="bg-[#dde2f1] rounded-2xl px-4 pt-4 pb-0 shadow flex flex-col w-full h-full">
          <div class="font-bold text-[#23255f] text-lg mb-3">Rapport des Tâches :</div>
          <div class="flex flex-col gap-2 mb-2">
            <div class="flex justify-between items-center bg-[#9099cd] text-white font-bold text-base rounded-xl px-3 py-2">
              <span>À faire</span>
              <span>{{ $count_todo }}</span>
            </div>
            <div class="flex justify-between items-center bg-[#646db1] text-white font-bold text-base rounded-xl px-3 py-2">
              <span>En cours</span>
              <span>{{ $count_progress }}</span>
            </div>
            <div class="flex justify-between items-center bg-[#cad3e9] text-[#23255f] font-bold text-base rounded-xl px-3 py-2">
              <span>Terminées</span>
              <span>{{ $count_done }}</span>
            </div>
          </div>
          <!-- Zone complémentaire pour alignement bas -->
          <div class="flex-1"></div>
          <div class="bg-[#dde2f1] rounded-b-2xl px-3 py-3 w-full flex items-center mt-2 min-h-[48px]">
            <span class="font-bold text-[#23255f] text-base">Total :</span>
            <span class="ml-auto font-bold text-[#23255f] text-base">{{ $count_total }}</span>
          </div>
        </div>
      </div>

      <!-- Bloc membres du projet + liens clients (même hauteur) -->
      <div class="col-span-3 flex flex-col justify-end">
        <div class="bg-[#e5e4f7] rounded-2xl px-8 py-5 shadow flex flex-col gap-3 h-full">
          <h2 class="font-bold text-3xl text-[#23255f] mb-2">Membres du projet :</h2>
          <div class="flex flex-wrap gap-3 items-center mb-2">
            @foreach($members as $m)
              <div class="flex items-center px-5 py-2 rounded-full bg-[#9099cd] text-white font-bold text-base gap-3 shadow"
                   style="@if($m->pivot->role === 'manager') background:#646db1; @elseif($m->pivot->role === 'client') background:#cad3e9;color:#23255f; @endif">
                {{ $m->name }}
                <span class="ml-2">{{ ucfirst($m->pivot->role) }}</span>
              </div>
            @endforeach
          </div>
          @if($currentRole === 'manager')
          <form action="{{ route('projects.addMember', $project->id_project) }}" method="POST" class="flex flex-row gap-3 mt-2 flex-wrap">
            @csrf
            <input type="text" id="user_search"
              name="user_search"
              class="rounded-xl px-4 py-2 border border-gray-300 text-base focus:outline-none"
              style="min-width:170px;max-width:300px"
              placeholder="Tapez un email ou un nom" autocomplete="off">
            <input type="hidden" name="user_id" id="user_id_selected">
            <select name="role" class="rounded-xl px-4 py-2 border border-gray-300 text-base focus:outline-none">
              <option value="associate">Associé</option>
              <option value="client">Client</option>
            </select>
            <button type="submit" class="px-6 py-2 text-base font-bold bg-[#646db1] text-white rounded-xl hover:bg-[#23255f] transition">Ajouter</button>
          </form>
          @endif
        </div>
        <!-- Liens clients aligné à gauche niveau Membres -->
        <div class="bg-[#dde2f1] rounded-2xl py-3 px-6 font-bold text-base text-[#23255f] shadow flex flex-row items-center w-full mt-1 gap-3">
          <span>Liens pour les clients</span>
          <span class="ml-4 font-mono bg-white px-2 py-1 rounded text-xs flex-1">{{ $clientUrl }}</span>
          <button onclick="navigator.clipboard.writeText('{{ $clientUrl }}')" class="px-3 py-1 bg-[#646db1] text-white rounded text-sm font-bold">Copier</button>
        </div>
      </div>
    </div>

    <!-- Ligne cartes accès et graphs -->
    <div class="grid grid-cols-4 gap-6 w-full mt-2">
      <a href="{{ route('projects.kanban', $project->id_project) }}" class="bg-[#23255f] rounded-2xl flex flex-col items-center justify-center py-10 text-white text-xl font-bold shadow hover:scale-105 transition min-h-[130px]">Accès KANBAN</a>
      <a href="{{ route('projects.roadmap', $project->id_project) }}" class="bg-[#646db1] rounded-2xl flex flex-col items-center justify-center py-10 text-white text-xl font-bold shadow hover:scale-105 transition min-h-[130px]">Accès ROADMAP</a>
      <div class="bg-[#dde2f1] rounded-2xl flex flex-col items-center justify-center py-8 text-[#23255f] text-xl font-bold shadow min-h-[130px]">
        <canvas id="kanbanPie" width="200" height="200"></canvas>
      </div>
      <div class="bg-[#cad3e9] rounded-2xl flex flex-col items-center justify-center py-8 text-[#23255f] text-xl font-bold shadow min-h-[130px]">
        <!-- Placez ici un second graphique ou vos contenus -->
      </div>
    </div>
  </div>
</div>

<!-- AUTOCOMPLETE + CHART JS SCRIPTS -->
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
        backgroundColor: ['#9099cd','#646db1','#cad3e9'],
        borderWidth: 2,
        borderColor: '#fff'
      }]
    },
    options: {
      responsive: false,
      plugins: {
        legend: {
          position: 'left',
          labels: {
            color: '#23255f',
            font: { size: 13 },
            boxWidth: 18,
            padding: 10
          }
        }
      }
    }
  });
}
</script>
@endsection
