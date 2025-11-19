@extends('layouts.app')
@section('content')
@php
    $currentMember = $project->members->firstWhere('id_user', auth()->id());
    $currentRole = $currentMember ? $currentMember->pivot->role : null;
    $clientCode = 'CLIENT-' . strtoupper(substr(hash('crc32', $project->id_project . 'SECRET_SALT'),0,8));
    $clientUrl = route('projects.sharedView', ['project' => $project->id_project, 'code' => $clientCode]);
@endphp

<div class="px-6 py-8">
    <div class="max-w-7xl mx-auto flex flex-col gap-4">
        <div class="grid grid-cols-4 gap-6 items-stretch">
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
                    <div class="flex-1"></div>
                    <div class="bg-[#dde2f1] rounded-b-2xl px-3 py-3 w-full flex items-center mt-2 min-h-[48px]">
                        <span class="font-bold text-[#23255f] text-base">Total :</span>
                        <span class="ml-auto font-bold text-[#23255f] text-base">{{ $count_total }}</span>
                    </div>
                </div>
            </div>
            <div class="col-span-3 flex flex-col justify-end">
                <div class="bg-[#e5e4f7] rounded-2xl px-8 py-5 shadow flex flex-col gap-3 h-full">
                    <h2 class="font-bold text-3xl text-[#23255f] mb-2">Membres du projet :</h2>
                    <div class="flex flex-wrap gap-3 items-center mb-2">
                        @foreach($members as $m)
                        <div class="flex items-center px-5 py-2 rounded-full bg-[#9099cd] text-white font-bold text-base gap-3 shadow relative"
                             style="@if($m->pivot->role === 'manager') background:#646db1; @elseif($m->pivot->role === 'client') background:#cad3e9;color:#23255f; @endif">
                            {{ $m->name }}
                            <span class="ml-2">{{ ucfirst($m->pivot->role) }}</span>
                            @if($currentRole === 'manager')
                                @if($m->pivot->role !== 'manager')
                                    <form action="{{ route('projects.removeMember', [$project->id_project, $m->id_user]) }}" method="POST" class="ml-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-1 text-[#F7615F] hover:text-[#d64545] text-base leading-none font-bold" title="Retirer">&times;</button>
                                    </form>
                                    <button type="button"
                                        onclick="openEditModal({{ $m->id_user }}, '{{ $m->name }}', '{{ $m->pivot->role }}')"
                                        class="ml-1 text-[#646db1] hover:text-[#23255f] text-xl leading-none font-bold" title="Modifier">⋯</button>
                                @endif
                            @endif
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
                <div class="bg-[#dde2f1] rounded-2xl py-3 px-6 font-bold text-base text-[#23255f] shadow flex flex-row items-center w-full mt-1 gap-3">
                    <span>Liens pour les clients</span>
                    <span class="ml-4 font-mono bg-white px-2 py-1 rounded text-xs flex-1">{{ $clientUrl }}</span>
                    <button onclick="navigator.clipboard.writeText('{{ $clientUrl }}')" class="px-3 py-1 bg-[#646db1] text-white rounded text-sm font-bold">Copier</button>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-6 w-full mt-2">
            <a href="{{ route('projects.kanban', $project->id_project) }}" class="bg-[#23255f] rounded-2xl flex flex-col items-center justify-center py-10 text-white text-xl font-bold shadow hover:scale-105 transition min-h-[130px]">Accès KANBAN</a>
            <a href="{{ route('projects.roadmap', $project->id_project) }}" class="bg-[#646db1] rounded-2xl flex flex-col items-center justify-center py-10 text-white text-xl font-bold shadow hover:scale-105 transition min-h-[130px]">Accès ROADMAP</a>
            <div class="bg-[#dde2f1] rounded-2xl flex flex-col items-center justify-center py-8 text-[#23255f] text-xl font-bold shadow min-h-[130px]">
                <canvas id="kanbanPie" width="200" height="200"></canvas>
            </div>
            <div class="bg-[#cad3e9] rounded-2xl flex flex-col items-center justify-center py-8 shadow min-h-[130px] w-full">
                <canvas id="progressCurve" width="220" height="120"></canvas>
                <div class="mt-2 text-[#23255f] text-base font-bold">Avancement du projet</div>
            </div>
        </div>
    </div>
</div>

<div id="editMemberModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-[#232332] rounded-2xl shadow-2xl w-96 max-w-full p-7 border border-[#31313f]">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-white">Modifier le rôle</h3>
            <button onclick="closeEditModal()" class="text-[#bbbce1] hover:text-white text-2xl leading-none font-semibold">&times;</button>
        </div>
        <div class="mb-4">
            <p class="text-[#bbbce1] mb-3">Membre : <span class="font-bold text-white" id="memberName"></span></p>
            <p class="text-[#bbbce1] mb-3">Rôle actuel : <span class="font-bold text-white" id="memberRole"></span></p>
        </div>
        <form id="editMemberForm" method="POST" class="flex flex-col gap-4">
            @csrf
            @method('PUT')
            <select name="role" id="roleSelect" class="rounded-xl px-4 py-2 border border-[#373755] bg-[#181826] text-[#E6E8F5] focus:ring-2 focus:ring-[#6D80EF] focus:outline-none">
                <option value="associate">Associé</option>
                <option value="client">Client</option>
            </select>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="bg-[#BBBCE1] text-[#232332] px-4 py-2 rounded-xl font-semibold hover:bg-[#C9A8FD] transition">
                    Annuler
                </button>
                <button type="submit" class="bg-[#8EE6D7] text-[#232332] px-4 py-2 rounded-xl font-semibold hover:bg-[#A4F2E3] transition">
                    Sauvegarder
                </button>
            </div>
        </form>
    </div>
</div>

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
function openEditModal(userId, userName, userRole) {
    document.getElementById('memberName').innerText = userName;
    document.getElementById('memberRole').innerText = userRole.charAt(0).toUpperCase() + userRole.slice(1);
    document.getElementById('roleSelect').value = userRole;
    document.getElementById('editMemberForm').action = `/projects/{{ $project->id_project }}/members/${userId}/update-role`;
    document.getElementById('editMemberModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editMemberModal').classList.add('hidden');
}
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
const curveCtx = document.getElementById('progressCurve');
if(curveCtx) {
    new Chart(curveCtx, {
        type: 'line',
        data: {
            labels: ['À faire','En cours','Terminées'],
            datasets: [{
                label: 'Avancement',
                data: [{{ $count_todo }}, {{ $count_progress }}, {{ $count_done }}],
                fill: true,
                borderColor: '#646db1',
                backgroundColor: 'rgba(100,109,177,0.23)',
                pointBackgroundColor: '#646db1',
                pointBorderColor: '#646db1',
                tension: 0.25
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#23255f', font: { weight: 'bold' } } },
                y: { ticks: { color: '#23255f', font: { weight: 'bold' } }, beginAtZero:true }
            }
        }
    });
}
</script>
@endsection
