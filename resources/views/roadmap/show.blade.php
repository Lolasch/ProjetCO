@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <h1 class="text-2xl font-bold mb-4">Roadmap : {{ $project->name }}</h1>

    <!-- Bouton pour ajouter un sprint -->
    <a href="{{ route('sprints.create', $project->id_project) }}"
       class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded mb-4 inline-block">
       + Ajouter un sprint
    </a>

    <!-- Calendrier des sprints -->
    <div id="calendar" class="mt-6 border rounded-lg shadow p-4"></div>
</div>

<!-- Modale Epic -->
<div id="epicModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-96 max-w-full p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 id="epicTitle" class="text-lg font-bold"></h3>
            <button onclick="closeEpicModal()" class="text-gray-600 hover:text-gray-800">&times;</button>
        </div>

        <div id="epicTasks" class="space-y-2 mb-4">
            <!-- Les tâches seront insérées ici -->
        </div>

        <div class="flex justify-between">
            <a id="addTaskBtn" href="#" class="bg-green-500 px-3 py-1 rounded text-white hover:bg-green-600 text-sm">+ Ajouter tâche</a>
            <div>
                <a id="editEpicBtn" href="#" class="bg-yellow-500 px-3 py-1 rounded text-white hover:bg-yellow-600 text-sm">Modifier</a>
                <form id="deleteEpicForm" action="#" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 px-3 py-1 rounded text-white hover:bg-red-600 text-sm">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
function openEpicModal(epic) {
    document.getElementById('epicTitle').innerText = epic.title;
    document.getElementById('epicTasks').innerHTML = '';

    if(epic.tasks && epic.tasks.length) {
        epic.tasks.forEach(task => {
            let taskEl = document.createElement('div');
            taskEl.classList.add('p-2', 'border', 'rounded', 'text-sm', 'flex', 'justify-between', 'items-center', 'font-medium');

            // Couleur du statut en français
            let statusColor = 'bg-gray-400 text-white';
            let statusText = task.status || 'À faire';
            if(statusText === 'À faire') statusColor = 'bg-red-500 text-white';
            else if(statusText === 'En cours') statusColor = 'bg-yellow-400 text-black';
            else if(statusText === 'Terminé') statusColor = 'bg-green-500 text-white';

            taskEl.innerHTML = `<span>${task.title}</span> <span class="px-2 py-1 rounded ${statusColor}">${statusText}</span>`;
            document.getElementById('epicTasks').appendChild(taskEl);
        });
    } else {
        let noTask = document.createElement('div');
        noTask.classList.add('text-gray-500', 'italic', 'text-sm');
        noTask.innerText = 'Aucune tâche pour cet epic.';
        document.getElementById('epicTasks').appendChild(noTask);
    }

    document.getElementById('addTaskBtn').href = `/epics/${epic.id}/tasks/create`;
    document.getElementById('editEpicBtn').href = `/epics/${epic.id}/edit`;
    document.getElementById('deleteEpicForm').action = `/epics/${epic.id}`;

    document.getElementById('epicModal').classList.remove('hidden');
    document.getElementById('epicModal').classList.add('flex');
}

function closeEpicModal() {
    document.getElementById('epicModal').classList.add('hidden');
    document.getElementById('epicModal').classList.remove('flex');
}

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
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
                id: 'sprint-{{ $sprint->id_sprint }}',
                title: '{{ $sprint->name }}',
                start: '{{ $sprint->start_date }}',
                end: '{{ $sprint->end_date }}',
                color: '{{ $sprint->color ?? "#1e40af" }}',
                extendedProps: {
                    epics: [
                        @foreach($sprint->epics as $epic)
                        {
                            id: '{{ $epic->id_epic }}',
                            title: '{{ $epic->name }}',
                            tasks: [
                                @foreach($epic->tasks as $task)
                                {
                                    title: '{{ $task->name }}',
                                    status: '{{ $task->status_fr ?? ($task->status ?? "À faire") }}'
                                },
                                @endforeach
                            ]
                        },
                        @endforeach
                    ]
                }
            },
            @endforeach
        ],
        eventContent: function(arg) {
            let sprintEl = document.createElement('div');
            sprintEl.classList.add('p-1');

            let titleEl = document.createElement('div');
            titleEl.innerHTML = `<strong>${arg.event.title}</strong>`;
            sprintEl.appendChild(titleEl);

            let btnContainer = document.createElement('div');
            btnContainer.classList.add('flex', 'justify-between', 'mt-1');

            btnContainer.innerHTML = `
                <a href="/sprints/${arg.event.id.split('-')[1]}/epics/create" class="text-sm bg-green-500 px-2 py-1 rounded text-white hover:bg-green-600">+ Epic</a>
                <div>
                    <a href="/sprints/${arg.event.id.split('-')[1]}/edit" class="text-sm bg-yellow-500 px-2 py-1 rounded text-white hover:bg-yellow-600">Modifier</a>
                    <form action="/sprints/${arg.event.id.split('-')[1]}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm bg-red-500 px-2 py-1 rounded text-white hover:bg-red-600">Supprimer</button>
                    </form>
                </div>
            `;
            sprintEl.appendChild(btnContainer);

            let epicsContainer = document.createElement('div');
            epicsContainer.classList.add('flex', 'space-x-2', 'mt-2', 'flex-wrap');

            arg.event.extendedProps.epics.forEach(epic => {
                let epicEl = document.createElement('div');
                epicEl.classList.add('px-3', 'py-2', 'bg-gray-800', 'rounded', 'text-sm', 'text-white', 'cursor-pointer', 'font-semibold');
                epicEl.innerText = epic.title;
                epicEl.onclick = () => openEpicModal(epic);
                epicsContainer.appendChild(epicEl);
            });

            sprintEl.appendChild(epicsContainer);

            return { domNodes: [sprintEl] };
        }
    });

    calendar.render();
});
</script>
@endsection
