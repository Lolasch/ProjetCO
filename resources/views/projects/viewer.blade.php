@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-8 text-center text-[#1c2352]">
        Vue externe du projet {{ $project->name }}
    </h1>

    <!-- Statistiques & Membres -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-[#dde2f1] rounded-2xl p-6">
            <div class="font-bold mb-2 text-[#23255f] text-xl">Statistiques des tâches :</div>
            <ul class="space-y-1 text-[#23255f] text-base">
                <li>À faire : <span class="font-semibold">{{ $count_todo }}</span></li>
                <li>En cours : <span class="font-semibold">{{ $count_progress }}</span></li>
                <li>Terminées : <span class="font-semibold">{{ $count_done }}</span></li>
                <li class="font-bold mt-3">Total : {{ $count_total }}</li>
            </ul>
        </div>
        <div class="bg-[#e5e4f7] rounded-2xl p-6">
            <div class="font-bold mb-2 text-[#23255f] text-xl">Membres du projet :</div>
            <ul class="flex flex-wrap gap-3">
            @foreach($members as $m)
                <li class="px-4 py-2 rounded-full font-semibold shadow
                    @if($m->pivot->role === 'manager') bg-[#646db1] text-white
                    @elseif($m->pivot->role === 'associate') bg-[#9099cd] text-white
                    @else bg-[#cad3e9] text-[#23255f]
                    @endif">
                    {{ $m->name }} <span class="ml-2 text-sm">{{ ucfirst($m->pivot->role) }}</span>
                </li>
            @endforeach
            </ul>
        </div>
    </div>

    <!-- Graphe diagramme -->
    <div class="mb-12">
        <h2 class="font-bold mb-4 text-[#1c2352] text-xl">Diagramme des statuts de tâches</h2>
        <div class="flex justify-center">
            <canvas id="kanbanPie" style="max-width:340px;max-height:280px"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('kanbanPie');
if(ctx) {
    new Chart(ctx, {
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
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: '#1c2352',
                        font: { size: 13 }
                    }
                }
            }
        }
    });
}
</script>
@endsection
