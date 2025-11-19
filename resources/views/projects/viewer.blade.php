@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-8 text-center text-[#d4d6f5]">
        Vue externe du projet {{ $project->name }}
    </h1>
    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-[#222235] rounded-2xl p-6 border border-[#373755]">
            <div class="font-bold mb-2 text-[#8ee6d7] text-xl">Statistiques des tâches :</div>
            <ul class="space-y-1 text-[#e6e8f5] text-base">
                <li>À faire : <span class="font-semibold text-[#C1B9F7]">{{ $count_todo }}</span></li>
                <li>En cours : <span class="font-semibold text-[#FFE27B]">{{ $count_progress }}</span></li>
                <li>Terminées : <span class="font-semibold text-[#7DE47B]">{{ $count_done }}</span></li>
                <li class="font-bold mt-3 text-[#8ee6d7]">Total : {{ $count_total }}</li>
            </ul>
        </div>
        <div class="bg-[#222235] rounded-2xl p-6 border border-[#373755]">
            <div class="font-bold mb-2 text-[#8ee6d7] text-xl">Membres du projet :</div>
            <ul class="flex flex-wrap gap-3">
            @foreach($members as $m)
                <li class="px-4 py-2 rounded-full font-semibold shadow
                    @if($m->pivot->role === 'manager') bg-[#39356d] text-[#8ee6d7]
                    @elseif($m->pivot->role === 'associate') bg-[#444488] text-[#e6e8f5]
                    @else bg-[#21213c] text-[#FFE27B]
                    @endif">
                    {{ $m->name }} <span class="ml-2 text-sm">{{ ucfirst($m->pivot->role) }}</span>
                </li>
            @endforeach
            </ul>
        </div>
    </div>

    <div class="mb-12 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-[#21213c] rounded-2xl py-6 flex flex-col items-center border border-[#373755]">
            <h2 class="font-bold mb-4 text-[#C1B9F7] text-lg">Diagramme des statuts de tâches</h2>
            <canvas id="kanbanPie" style="max-width:320px;max-height:220px"></canvas>
        </div>
        <div class="bg-[#21213c] rounded-2xl py-6 flex flex-col items-center border border-[#373755]">
            <h2 class="font-bold mb-4 text-[#C1B9F7] text-lg">Courbe d'avancement</h2>
            <canvas id="kanbanLine" style="max-width:320px;max-height:220px"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const pie = document.getElementById('kanbanPie');
const line = document.getElementById('kanbanLine');
if(pie) {
    new Chart(pie, {
        type: 'pie',
        data: {
            labels: ['À faire', 'En cours', 'Terminées'],
            datasets: [{
                label: 'Statut des tâches',
                data: [{{ $count_todo }}, {{ $count_progress }}, {{ $count_done }}],
                backgroundColor: [
                    '#3c4ea2',   // À faire
                    '#ffe27b',   // En cours
                    '#7de47b'    // Terminées
                ],
                borderWidth: 2,
                borderColor: '#2b2c41'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: '#d4d6f5',
                        font: { size: 13 }
                    }
                }
            }
        }
    });
}
if(line) {
    new Chart(line, {
        type: 'line',
        data: {
            labels: ['À faire','En cours','Terminées'],
            datasets: [{
                label: 'Evolution',
                data: [{{ $count_todo }}, {{ $count_progress }}, {{ $count_done }}],
                fill: true,
                borderColor: '#8ee6d7',
                backgroundColor: 'rgba(142,230,215,0.15)',
                pointBackgroundColor: '#8ee6d7',
                pointBorderColor: '#8ee6d7',
                tension: 0.29
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#d4d6f5', font: { weight: 'bold' } } },
                y: { ticks: { color: '#d4d6f5', font: { weight: 'bold' } }, beginAtZero: true }
            }
        }
    });
}
</script>
@endsection
