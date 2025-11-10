@extends('layouts.app')

@section('content')
<div class="px-8 py-6">
    <h1 class="text-3xl font-bold mb-6 text-center">Kanban - {{ $project->name }}</h1>

    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">
            Retour aux projets
        </a>
    </div>

    <div class="mb-6">
        @if($sprints->count() > 0)
            <form method="GET" action="{{ route('projects.kanban', $project->id_project) }}">
                <label for="sprint">Sprint :</label>
                <select name="sprint" id="sprint" onchange="this.form.submit()" class="border rounded p-1">
                    @foreach($sprints as $s)
                        <option value="{{ $s->id_sprint }}" {{ ($sprint && $sprint->id_sprint == $s->id_sprint) ? 'selected' : '' }}>
                            {{ $s->name }} ({{ $s->start_date }} → {{ $s->end_date }})
                        </option>
                    @endforeach
                </select>
            </form>
        @else
            <a href="{{ route('projects.roadmap', $project->id_project) }}"
               class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
               Créer un sprint
            </a>
        @endif
    </div>

    <div class="flex space-x-4 overflow-x-auto">
        @foreach(['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé'] as $statusKey => $statusLabel)
            <div class="bg-gray-100 rounded-2xl p-4 w-80 flex flex-col">
                <h2 class="text-xl font-bold mb-4">
                    {{ $statusLabel }}
                    <span class="text-sm text-gray-600">({{ $tasksCount[$statusKey] ?? 0 }})</span>
                </h2>
                <div class="flex flex-col space-y-2 min-h-[50px] kanban-column" data-status="{{ $statusKey }}">
                    @if(isset($tasksByStatus[$statusKey]))
                        @foreach($tasksByStatus[$statusKey] as $task)
                            @php
                                $isDraggable =
                                    $currentRole === 'manager' ||
                                    ($currentRole === 'associate' && auth()->user()->id_user == $task->assigned_to);
                                $alerte = false;
                                if($task->due_date){
                                    $now = \Carbon\Carbon::today();
                                    $due = \Carbon\Carbon::parse($task->due_date)->startOfDay();
                                    $diff = $now->diffInDays($due, false);
                                    if ($diff < 0) {
                                        $alerte = ["Retard : échue depuis " . abs($diff) . " jour(s)", 'bg-red-100 text-red-800'];
                                    } elseif ($diff == 0) {
                                        $alerte = ["Dernier jour ! Échéance aujourd'hui", 'bg-orange-100 text-orange-800'];
                                    } elseif ($diff <= 2) {
                                        $alerte = ["Attention : échéance dans $diff jour(s)", 'bg-yellow-100 text-yellow-800'];
                                    }
                                }
                            @endphp
                            <div
                                class="bg-white rounded p-2 shadow flex flex-col kanban-task"
                                @if($isDraggable) draggable="true" @endif
                                data-id="{{ $task->id_task }}"
                                data-title="{{ $task->title }}"
                                data-description="{{ $task->description ?? '' }}"
                                data-status="{{ $task->status }}"
                                data-epic="{{ $task->epic_id ?? '' }}"
                                data-assigned_to="{{ $task->assigned_to ?? '' }}"
                                data-due_date="{{ $task->due_date }}"
                            >
                                <div class="flex justify-between items-center">
                                    <span>{{ $task->title }}</span>
                                    @if($task->epic)
                                        <span class="text-xs text-white bg-blue-500 rounded px-1 py-0.5 ml-2">
                                            Epic : {{ $task->epic->name }}
                                        </span>
                                    @endif
                                </div>
                                @if($task->due_date)
                                    <span class="text-xs text-gray-700 ml-2">
                                        Échéance : {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                    </span>
                                @endif
                                @if($alerte)
                                    <span class="text-xs font-semibold px-2 py-1 mt-1 rounded {{ $alerte[1] }}">{{ $alerte[0] }}</span>
                                @endif
                                <div class="flex justify-end mt-2">
                                    <button
                                        type="button"
                                        class="ml-2 px-2 py-1 bg-gray-200 text-blue-600 rounded hover:bg-blue-100 focus:outline-none"
                                        onclick="openTaskModal(this, '{{ auth()->user()->id_user }}', '{{ $currentRole }}'); event.stopPropagation();"
                                        title="Visualiser / éditer"
                                    >Visualiser</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                @if($sprint && $currentRole === 'manager')
                    <form action="{{ route('kanban.tasks.store', [$project->id_project, $sprint->id_sprint]) }}" method="POST" class="mt-2">
                        @csrf
                        <input type="hidden" name="status" value="{{ $statusKey }}">
                        <input type="text" name="title" placeholder="Titre de la tâche" class="w-full border rounded p-1 text-sm mb-1" required>
                        <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-sm w-full">
                            Ajouter
                        </button>
                    </form>
                @elseif($sprint)
                    <div class="mt-2 text-gray-500 text-sm italic">
                        <!-- Aucun formulaire d'ajout pour associé/client -->
                    </div>
                @else
                    <div class="mt-2 text-gray-500 text-sm italic">
                        Aucun sprint actif pour ajouter des tâches.
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- MODAL D'ÉDITION -->
<div id="taskModal" class="fixed inset-0 bg-transparent hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">
        <h2 class="text-xl font-bold mb-4">Modifier la tâche</h2>
       <form id="taskForm" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="block text-sm font-medium">Titre :</label>
        <input type="text" name="title" id="taskTitle" class="border rounded w-full p-2" required>
    </div>
    <div class="mb-3">
        <label class="block text-sm font-medium">Description :</label>
        <textarea name="description" id="taskDescription" rows="3" class="border rounded w-full p-2"></textarea>
    </div>
    <div class="mb-3">
        <label class="block text-sm font-medium">Associer à un Epic :</label>
        <select name="epic_id" id="taskEpic" class="border rounded w-full p-2">
            <option value="">Aucun</option>
            @if(isset($sprint->epics))
                @foreach($sprint->epics as $epic)
                    <option value="{{ $epic->id_epic }}">{{ $epic->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="mb-3">
        <label class="block text-sm font-medium" for="taskAssignee">Assigner à un associé :</label>
        <select name="assigned_to" id="taskAssignee" class="border rounded w-full p-2">
            <option value="">-- Aucun --</option>
            @foreach($associates as $user)
                <option value="{{ $user->id_user }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="block text-sm font-medium" for="taskDueDate">Date d'échéance :</label>
        <input type="date" name="due_date" id="taskDueDate" class="border rounded w-full p-2" required>
    </div>
    <div class="flex justify-between mt-4">
        <button type="button" onclick="closeTaskModal()" class="bg-gray-400 text-white px-3 py-1 rounded">Fermer</button>
        <div id="actionButtons" class="flex gap-2"></div>
    </div>
</form>


    </div>
</div>


<script>
let dragged = null;


// Drag & Drop
document.querySelectorAll('.kanban-task').forEach(task => {
    // attention : drag que si data-draggable
    if (task.hasAttribute('draggable') && task.getAttribute('draggable') === "true") {
        task.addEventListener('dragstart', e => {
            dragged = task;
            setTimeout(() => (task.style.display = 'none'), 0);
        });
        task.addEventListener('dragend', e => {
            dragged = null;
            task.style.display = '';
        });
    }
});


document.querySelectorAll('.kanban-column').forEach(column => {
    column.addEventListener('dragover', e => e.preventDefault());
    column.addEventListener('drop', function(e) {
        e.preventDefault();
        if (!dragged) return;
        this.appendChild(dragged);
        const taskId = dragged.dataset.id;
        fetch(`/kanban/tasks/${taskId}/move`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: this.dataset.status })
        });
    });
});


function openTaskModal(button, currentUserId, currentRole) {
    const taskDiv = button.closest('.kanban-task');
    const modal = document.getElementById('taskModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');


    document.getElementById('taskTitle').value = taskDiv.dataset.title;
    document.getElementById('taskDescription').value = taskDiv.dataset.description;
    document.getElementById('taskEpic').value = taskDiv.dataset.epic || '';
    document.getElementById('taskAssignee').value = taskDiv.dataset.assigned_to || '';
    document.getElementById('taskDueDate').value = taskDiv.dataset.due_date || '';


    const taskId = taskDiv.dataset.id;
    document.getElementById('taskForm').action = `/tasks/${taskId}`;


    const assignedTo = taskDiv.dataset.assigned_to;
    const actionButtons = document.getElementById('actionButtons');
    actionButtons.innerHTML = '';


    if (currentRole === 'manager') {
        actionButtons.innerHTML = `
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Enregistrer</button>
            <button type="button" id="deleteButton" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Supprimer</button>
        `;
        setTimeout(() => {
            const deleteBtn = document.getElementById('deleteButton');
            if (deleteBtn) {
                deleteBtn.onclick = () => deleteTask(taskId);
            }
        }, 50);
        document.getElementById('taskTitle').disabled = false;
        document.getElementById('taskDescription').disabled = false;
        document.getElementById('taskEpic').disabled = false;
        document.getElementById('taskAssignee').disabled = false;
    } else if (
        currentRole === 'associate' &&
        String(currentUserId) === String(assignedTo)
    ) {
        actionButtons.innerHTML = `
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Enregistrer</button>
            <button type="button" id="deleteButton" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Supprimer</button>
        `;
        setTimeout(() => {
            const deleteBtn = document.getElementById('deleteButton');
            if (deleteBtn) {
                deleteBtn.onclick = () => deleteTask(taskId);
            }
        }, 50);
        document.getElementById('taskTitle').disabled = false;
        document.getElementById('taskDescription').disabled = false;
        document.getElementById('taskEpic').disabled = false;
        document.getElementById('taskAssignee').disabled = true;
    } else {
        actionButtons.innerHTML = '';
        document.getElementById('taskTitle').disabled = true;
        document.getElementById('taskDescription').disabled = true;
        document.getElementById('taskEpic').disabled = true;
        document.getElementById('taskAssignee').disabled = true;
    }
}


function closeTaskModal() {
    const modal = document.getElementById('taskModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}


function deleteTask(id) {
    if (confirm('Supprimer cette tâche ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/tasks/${id}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
