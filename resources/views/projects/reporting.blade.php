@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6 text-center text-[#1c2352]">Reporting du projet {{ $project->name }}</h1>

    <div class="mb-10 flex flex-col md:flex-row md:justify-between gap-4">
        <div class="bg-[#e4e3ef] rounded-2xl p-6 min-w-[220px]">
            <div class="font-bold mb-2 text-[#26428b]">Statistiques des tâches :</div>
            <ul class="space-y-1">
                <li>À faire : {{ $count_todo }}</li>
                <li>En cours : {{ $count_progress }}</li>
                <li>Terminées : {{ $count_done }}</li>
                <li class="font-bold">Total : {{ $count_total }}</li>
            </ul>
        </div>
        <div class="bg-[#e4e3ef] rounded-2xl p-6 min-w-[220px]">
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
            @php
                $currentMember = $project->members->firstWhere('id_user', auth()->id());
                $currentRole = $currentMember ? $currentMember->pivot->role : null;
            @endphp
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
    </div>

    <div class="mb-10">
        <div class="bg-[#bfcbe1] rounded-2xl px-6 py-4 flex flex-col items-center">
            <div class="font-bold text-[#26428b] mb-2">Accès client :</div>
            <span class="text-sm text-[#1c2352] break-all">Code ou lien à donner au client : <span class="font-mono bg-white px-2 py-1 rounded">...</span></span>
        </div>
    </div>

    <div class="mb-12">
        <h2 class="font-bold mb-3 text-[#1c2352]">Diagramme des statuts de tâches</h2>
        <canvas id="kanbanPie" width="400" height="230"></canvas>
    </div>

    <div class="flex justify-center">
        <a href="{{ route('dashboard') }}" class="bg-gray-300 px-4 py-2 rounded text-gray-700 hover:bg-gray-400">Retour au dashboard</a>
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
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const data = {
    labels: ['À faire', 'En cours', 'Terminées'],
    datasets: [{
        label: 'Statut des tâches',
        data: [{{ $count_todo }}, {{ $count_progress }}, {{ $count_done }}],
        backgroundColor: ['#fbbf24','#6366f1','#16a34a'],
    }]
};
const ctx = document.getElementById('kanbanPie');
if(ctx) new Chart(ctx, { type: 'pie', data });
</script>
@endsection
