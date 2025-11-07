@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6 text-center text-[#1c2352]">
        Vue externe du projet {{ $project->name }}
    </h1>

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
        </div>
    </div>

    <div class="mb-12">
        <h2 class="font-bold mb-3 text-[#1c2352]">Diagramme des statuts de tâches</h2>
        <div class="flex justify-center">
            <canvas id="kanbanPie" style="max-width:400px;max-height:300px"></canvas>
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
</script>
@endsection
