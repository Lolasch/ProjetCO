@extends('layouts.app')

@section('content')
<div class="w-full px-4 py-6 flex flex-col items-center">
    <div class="w-full max-w-[1600px] mx-auto">
        <div class="flex items-center gap-6 mb-6">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center bg-[#5b6cb2] text-white font-bold px-4 py-2 rounded-full text-sm shadow-md hover:bg-[#39356d] transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Retour aux projets
            </a>
        </div>

        @if($currentRole === 'manager')
            <div class="flex flex-wrap gap-3 mb-5 justify-end">
                <a href="{{ route('sprints.create', $project->id_project) }}"
                   class="bg-[#8fc8b2] hover:bg-[#4e9893] text-white font-semibold px-4 py-2 rounded-full text-sm shadow-md transition">
                   + Ajouter un sprint
                </a>
                <a href="{{ route('releases.create', $project->id_project) }}"
                   class="bg-[#e7b8e8] hover:bg-[#f9c6f4] text-white font-semibold px-4 py-2 rounded-full text-sm shadow-md transition">
                   + Ajouter une release
                </a>
            </div>
        @endif

        <div id="calendar" class="calendar-container"></div>
    </div>
</div>

<!-- EPIC MODAL -->
<div id="epicModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-[#19181f] rounded-2xl shadow-2xl w-96 max-w-full p-5 border border-[#3a324a]">
        <div class="flex justify-between items-center mb-4">
            <h3 id="epicTitle" class="text-lg font-bold text-white"></h3>
            <button onclick="closeEpicModal()" class="text-[#b1b9ea] hover:text-white text-2xl leading-none">&times;</button>
        </div>
        <div id="epicTasks" class="mb-2 max-h-72 overflow-y-auto pr-1 space-y-2"></div>
        @if($currentRole === 'manager')
        <div class="flex gap-2 mt-4 justify-end">
            <a id="editEpicBtn" href="#" class="bg-[#A4F2E3] text-[#232332] px-3 py-1 rounded-lg font-semibold hover:bg-[#71E0CA] transition">Modifier</a>
            <form id="deleteEpicForm" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-[#DE2E4B] px-3 py-1 rounded-lg text-white text-sm font-semibold hover:bg-[#d64545] transition">Supprimer</button>
            </form>
        </div>
        @endif
    </div>
</div>

<!-- RELEASE MODAL -->
<div id="releaseModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-[#19181f] rounded-2xl shadow-2xl w-96 max-w-full p-5 border border-[#3a324a]">
        <div class="flex justify-between items-center mb-4">
            <h3 id="releaseTitle" class="text-lg font-bold text-white"></h3>
            <button onclick="closeReleaseModal()" class="text-[#b1b9ea] hover:text-white text-2xl leading-none">&times;</button>
        </div>
        <div id="releaseInfo" class="mb-4 text-sm text-[#d4d5f8]"></div>
        @if($currentRole === 'manager')
        <form id="deleteReleaseForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-[#DE2E4B] px-3 py-1.5 rounded-lg text-white text-sm font-semibold hover:bg-[#d64545] transition">Supprimer</button>
        </form>
        @endif
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
function hexToRgba(hex, alpha) {
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);
    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}

function openEpicModal(epic) {
    document.getElementById('epicTitle').innerText = epic.title;
    const epicTasksDiv = document.getElementById('epicTasks');
    epicTasksDiv.innerHTML = '';
    epic.tasks.forEach(task => {
        let statusText = 'À faire';
        let statusColor = 'bg-gray-300 text-gray-800';
        if(task.status === 'in_progress') { statusText = 'En cours'; statusColor = 'bg-yellow-300 text-yellow-800'; }
        else if(task.status === 'done') { statusText = 'Terminé'; statusColor = 'bg-green-300 text-green-800'; }
        let taskEl = document.createElement('div');
        taskEl.classList.add('mb-2', 'p-2', 'border', 'border-[#343347]', 'rounded-xl', 'flex', 'justify-between', 'items-center', 'bg-[#232332]');
        taskEl.innerHTML = `
            <div class="flex items-center gap-2">
                <span class="font-semibold text-sm text-white">${task.title}</span>
                <span class="px-2 py-1 rounded ${statusColor} text-xs">${statusText}</span>
            </div>
        `;
        epicTasksDiv.appendChild(taskEl);
    });
    @if($currentRole === 'manager')
    document.getElementById('editEpicBtn').href = `/epics/${epic.id}/edit`;
    document.getElementById('deleteEpicForm').action = `/epics/${epic.id}`;
    @endif
    document.getElementById('epicModal').classList.remove('hidden');
}
function closeEpicModal() {
    document.getElementById('epicModal').classList.add('hidden');
}

function openReleaseModal(data) {
    document.getElementById('releaseTitle').innerText = data.title;
    document.getElementById('releaseInfo').innerHTML = `
        <p class="mb-1">Date de release : <span class="font-semibold">${data.release_date}</span></p>
        <p class="mb-1">Projet : <span class="font-semibold">{{ $project->name }}</span></p>
    `;
    @if($currentRole === 'manager')
    document.getElementById('deleteReleaseForm').action = `/releases/${data.id}`;
    @endif
    document.getElementById('releaseModal').classList.remove('hidden');
}
function closeReleaseModal() {
    document.getElementById('releaseModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 650,
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine'
        },
        events: [
            @foreach($sprints as $sprint)
            {
                id: @json("sprint-{$sprint->id_sprint}"),
                title: @json($sprint->name),
                start: @json($sprint->start_date),
                end: @json($sprint->end_date),
                color: @json($sprint->color ?? '#5b6cb2'),
                extendedProps: {
                    epics: [
                        @foreach($sprint->epics as $epic)
                        {
                            id: @json($epic->id_epic),
                            title: @json($epic->name),
                            tasks: [
                                @foreach($epic->tasks as $task)
                                {
                                    id: @json($task->id_task),
                                    title: @json($task->title),
                                    status: @json($task->status)
                                },
                                @endforeach
                            ]
                        },
                        @endforeach
                    ]
                }
            },
            @endforeach
            @foreach($releases as $release)
            {
                id: @json("release-{$release->id}"),
                title: @json($release->name),
                start: @json($release->release_date),
                color: @json($release->color ?? '#f97316'),
                extendedProps: { isRelease: true }
            },
            @endforeach
        ],
        eventContent: function(arg) {
            let el = document.createElement('div');
            el.classList.add('p-3', 'rounded-lg', 'shadow-sm');
            const transparentColor = hexToRgba(arg.event.backgroundColor, 0.5);
            el.style.backgroundColor = transparentColor;
            el.style.border = `1px solid ${hexToRgba(arg.event.backgroundColor, 0.8)}`;
            if(arg.event.extendedProps.isRelease){
                el.style.cursor = 'pointer';
                el.innerHTML = `<strong class="text-sm text-white">${arg.event.title}</strong>`;
                el.onclick = () => openReleaseModal({
                    id: arg.event.id.split('-')[1],
                    title: arg.event.title,
                    release_date: arg.event.start
                });
                return { domNodes: [el] };
            }
            let topRow = document.createElement('div');
            topRow.classList.add('flex', 'justify-between', 'items-center', 'mb-2');
            topRow.innerHTML = `
                <strong class="text-sm text-white">${arg.event.title}</strong>
                @if($currentRole === 'manager')
                <div class="flex gap-1">
                    <a href="/sprints/${arg.event.id.split('-')[1]}/edit"
                       class="text-[10px] bg-[#d4b068] px-2 py-0.5 rounded-md text-white hover:bg-[#c19a4e] font-semibold transition">Modifier</a>
                    <form action="/sprints/${arg.event.id.split('-')[1]}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-[10px] bg-[#e88d8b] px-2 py-0.5 rounded-md text-white hover:bg-[#d67673] font-semibold transition">Supprimer</button>
                    </form>
                </div>
                @endif
            `;
            el.appendChild(topRow);
            @if($currentRole === 'manager')
            let createEpicRow = document.createElement('div');
            createEpicRow.classList.add('mb-2');
            createEpicRow.innerHTML = `
                <a href="/sprints/${arg.event.id.split('-')[1]}/epics/create"
                   class="inline-block text-[10px] bg-[#8fc8b2] px-2 py-1 rounded-md text-white hover:bg-[#4e9893] font-semibold transition">Créer un Epic</a>
            `;
            el.appendChild(createEpicRow);
            @endif
            if(arg.event.extendedProps.epics && arg.event.extendedProps.epics.length > 0) {
                let epicsContainer = document.createElement('div');
                epicsContainer.classList.add('grid', 'grid-cols-6', 'gap-1.5');
                arg.event.extendedProps.epics.forEach(epic => {
                    let epicEl = document.createElement('div');
                    epicEl.classList.add('px-2', 'py-2', 'bg-[#1a1a24]', 'text-white', 'rounded', 'text-[9px]', 'cursor-pointer', 'hover:bg-[#252530]', 'transition', 'text-center', 'truncate', 'font-medium');
                    epicEl.innerText = epic.title;
                    epicEl.title = epic.title;
                    epicEl.onclick = () => openEpicModal(epic);
                    epicsContainer.appendChild(epicEl);
                });
                el.appendChild(epicsContainer);
            }
            return { domNodes: [el] };
        }
    });
    calendar.render();
});
</script>
@endsection
