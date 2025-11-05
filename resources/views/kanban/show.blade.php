@extends('layouts.app')

@section('content')
<div class="px-8 py-6">
    <h1 class="text-3xl font-bold mb-6 text-center">Kanban - {{ $project->name }}</h1>

    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">
            ← Retour aux projets
        </a>
    </div>

    <div class="mb-6">
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
    </div>

    <div class="flex space-x-4 overflow-x-auto">
        @foreach(['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé'] as $statusKey => $statusLabel)
            <div class="bg-gray-100 rounded-2xl p-4 w-80 flex flex-col">
                <h2 class="text-xl font-bold mb-4">{{ $statusLabel }}</h2>
                <div class="flex flex-col space-y-2 min-h-[50px] kanban-column" data-status="{{ $statusKey }}">
                    @if(isset($tasksByStatus[$statusKey]))
                        @foreach($tasksByStatus[$statusKey] as $task)
                            <div
                                class="bg-white rounded p-2 shadow flex justify-between items-center kanban-task"
                                draggable="true"
                                data-id="{{ $task->id_task }}"
                                data-title="{{ $task->title }}"
                                data-description="{{ $task->description ?? '' }}"
                                data-status="{{ $task->status }}"
                                data-epic="{{ $task->epic_id ?? '' }}"
                            >
                                <div>
                                    <span>{{ $task->title }}</span>
                                    @if($task->epic)
                                        <span class="text-xs text-white bg-blue-500 rounded px-1 py-0.5 ml-2">
                                            Epic : {{ $task->epic->name }}
                                        </span>
                                    @endif
                                </div>
                                <button
                                    type="button"
                                    class="ml-2 px-2 py-1 bg-gray-200 text-blue-600 rounded hover:bg-blue-100 focus:outline-none"
                                    onclick="openTaskModal(this); event.stopPropagation();"
                                    title="Visualiser / éditer"
                                >Visualiser</button>
                            </div>
                        @endforeach
                    @endif
                </div>

                <form action="{{ route('kanban.tasks.store', [$project->id_project, $sprint->id_sprint]) }}" method="POST" class="mt-2">
                    @csrf
                    <input type="hidden" name="status" value="{{ $statusKey }}">
                    <input type="text" name="title" placeholder="Titre de la tâche" class="w-full border rounded p-1 text-sm mb-1" required>
                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-sm w-full">
                        ➕ Ajouter
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>

<!-- MODAL D'ÉDITION -->
<div id="taskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
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
            <div class="flex justify-between mt-4">
                <button type="button" onclick="closeTaskModal()" class="bg-gray-400 text-white px-3 py-1 rounded">Fermer</button>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Enregistrer</button>
                    <button type="button" id="deleteButton" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Supprimer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let dragged = null;

// Drag & Drop
document.querySelectorAll('.kanban-task').forEach(task => {
    task.addEventListener('dragstart', e => {
        dragged = task;
        setTimeout(() => (task.style.display = 'none'), 0);
    });
    task.addEventListener('dragend', e => {
        dragged = null;
        task.style.display = '';
    });
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

// Modal avec data-attributes
function openTaskModal(button) {
    const taskDiv = button.closest('.kanban-task');
    const modal = document.getElementById('taskModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.getElementById('taskTitle').value = taskDiv.dataset.title;
    document.getElementById('taskDescription').value = taskDiv.dataset.description;
    document.getElementById('taskEpic').value = taskDiv.dataset.epic || '';

    const taskId = taskDiv.dataset.id;
    document.getElementById('taskForm').action = `/tasks/${taskId}`;

    document.getElementById('deleteButton').onclick = () => deleteTask(taskId);
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
