@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6 text-center">Reporting du projet {{ $project->name }}</h1>

    <div class="mb-6 flex justify-between">
        <div>
            <div class="font-bold mb-2">Statistiques des tâches :</div>
            <ul>
                <li>À faire : {{ $count_todo }}</li>
                <li>En cours : {{ $count_progress }}</li>
                <li>Terminées : {{ $count_done }}</li>
                <li class="font-bold">Total : {{ $count_total }}</li>
            </ul>
        </div>
        <div>
            <div class="font-bold mb-2">Membres du projet :</div>
            <ul>
                @foreach($members as $m)
                    <li>{{ $m->name }} <span class="text-xs text-gray-500">({{ $m->pivot->role }})</span></li>
                @endforeach
            </ul>
        </div>
    </div>

    <h2 class="font-bold mt-4 mb-2">Diagramme secteurs :</h2>
    <canvas id="kanbanPie" width="400" height="230"></canvas>

    <div class="mt-6">
        <a href="{{ route('dashboard') }}" class="bg-gray-300 px-4 py-2 rounded text-gray-700 hover:bg-gray-400">Retour au dashboard</a>
    </div>
</div>
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
