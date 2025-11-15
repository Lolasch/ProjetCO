@extends('layouts.app')

@section('content')
<div class="bg-black min-h-screen flex flex-col items-center py-10 w-full">

    <!-- Header modifié : Select custom label -->
    <div class="w-full max-w-[1400px] flex flex-row justify-between items-center mb-8">
<a href="{{ route('dashboard') }}"
   class="flex items-center bg-[#5b6cb2] text-white font-bold px-6 py-2 rounded-full text-base shadow">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    Retour aux projets
</a>



        <div class="flex items-center bg-[#5b6cb2] px-6 py-2 rounded-full">

            <span class="text-white font-bold text-lg mr-4">{{ $sprint ? $sprint->name : 'Aucun' }}</span>
            @if($sprints->count() > 0)
            <form method="GET" action="{{ route('projects.kanban', $project->id_project) }}" class="ml-2">
                <div class="relative w-fit max-w-[220px]">
                    <select name="sprint" id="sprint"
                        onchange="this.form.submit()"
                        class="bg-[#e3e6f3] border-0 py-1 px-4 pr-10 rounded-full text-black font-semibold text-sm w-fit max-w-[220px] appearance-none"
                        style="min-width: 0;">
                        <option value="" disabled selected hidden>Visualiser un autre sprint</option>
                        @foreach($sprints as $s)
                            @if(!$sprint || $s->id_sprint != $sprint->id_sprint)
                                <option value="{{ $s->id_sprint }}">
                                    {{ $s->name }} ({{ $s->start_date }} → {{ $s->end_date }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-[#39256b]"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </form>
            @endif
        </div>
    </div>

    <main class="w-full max-w-[1400px] flex flex-col lg:flex-row space-y-8 lg:space-y-0 lg:space-x-8 justify-center items-start">
        @foreach(['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé'] as $statusKey => $statusLabel)
        <section class="bg-[#19181b] rounded-xl px-5 py-8 w-full max-w-[400px] shadow-xl flex flex-col" style="height: 80vh;">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-bold text-white">{{ $statusLabel }}</h2>
                <span id="count-{{ $statusKey }}"
                    class="text-sm font-semibold bg-[#9066ad] text-white px-4 py-1 rounded-full ml-2">
                   {{ $tasksCount[$statusKey] ?? 0 }} tâche{{ ($tasksCount[$statusKey] ?? 0) > 1 ? 's' : '' }}
                </span>
            </div>
            <div class="flex flex-col gap-4 min-h-[40px] kanban-column overflow-y-auto"
                style="max-height: 62vh;" data-status="{{ $statusKey }}">
                @foreach($tasksByStatus[$statusKey] ?? [] as $task)
                @php
                    $isDraggable =
                        $currentRole === 'manager' ||
                        ($currentRole === 'associate' && auth()->user()->id_user == $task->assigned_to);
                    $alerte = false;
                    $alerte_color = '';
                    if($task->due_date){
                        $now = \Carbon\Carbon::today();
                        $due = \Carbon\Carbon::parse($task->due_date)->startOfDay();
                        $diff = $now->diffInDays($due, false);
                        if ($diff < 0) {
                            $alerte = "Retard : échue depuis " . abs($diff) . " jour(s)";
                            $alerte_color = 'bg-red-200 text-red-800';
                        } elseif ($diff == 0) {
                            $alerte = "Dernier jour ! Échéance aujourd'hui";
                            $alerte_color = 'bg-orange-200 text-orange-800';
                        } elseif ($diff <= 2) {
                            $alerte = "Attention : échéance dans $diff jour(s)";
                            $alerte_color = 'bg-orange-200 text-orange-800';
                        }
                    }
                @endphp
                <div
                    class="kanban-task status-{{ $statusKey }} bg-[#262432] rounded-xl shadow flex flex-col p-4 border border-[#24202a] hover:border-[#a36ec5] transition"
                    @if($isDraggable) draggable="true" @endif
                    data-id="{{ $task->id_task }}"
                    data-title="{{ $task->title }}"
                    data-description="{{ $task->description ?? '' }}"
                    data-status="{{ $statusKey }}"
                    data-epic="{{ $task->epic_id ?? '' }}"
                    data-assigned_to="{{ $task->assigned_to ?? '' }}"
                    data-due_date="{{ $task->due_date }}"
                    onclick="if(!window.dragged){openTaskModal(this, '{{ auth()->user()->id_user }}', '{{ $currentRole }}');}"
                    style="cursor:pointer;"
                >
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <span class="font-bold text-base text-[#ebeafd] break-words">{{ $task->title }}</span>
                        @if($task->epic)
                        <span class="bg-[#ba78e8] text-white text-xs rounded-full px-3 py-1 whitespace-nowrap">
                        {{ $task->epic->name }}
                        </span>
                        @endif
                    </div>
                    <span class="text-xs text-[#bac8df] mb-1">Échéance : {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}</span>
                    {{-- Plus de bouton Visualiser ici --}}
                    @if($alerte)
                        <div class="flex items-center justify-start mt-3">
                            <span class="inline-flex items-center px-3 py-1 {{ $alerte_color }} text-xs font-semibold rounded-lg">
                                {{ $alerte }}
                            </span>
                        </div>
                    @endif
                </div>
                @endforeach

                @if($sprint && $currentRole === 'manager')
                    <form action="{{ route('kanban.tasks.store', [$project->id_project, $sprint->id_sprint]) }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="status" value="{{ $statusKey }}">
                        <input type="text" name="title" placeholder="Titre de la tâche" class="w-full border border-[#4b4155] rounded-lg p-2 text-base mb-2 bg-[#1b1a20] text-white" required>
                        <button type="submit" class="bg-[#8fc8b2] text-white px-4 py-2 rounded-full hover:bg-[#4e9893] text-base w-full transition">
                            Ajouter
                        </button>
                    </form>
                @elseif($sprint)
                    <div class="mt-2 text-gray-400 text-xs italic text-center">Aucun formulaire d'ajout pour ce rôle.</div>
                @else
                    <div class="mt-2 text-gray-400 text-xs italic text-center">Aucun sprint actif pour ajouter des tâches.</div>
                @endif
            </div>
        </section>
        @endforeach
    </main>

    <!-- Modal édition tâche, ne change pas -->
    <div id="taskModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">
            <h2 class="text-xl font-bold mb-4 text-[#2d176b]">Modifier la tâche</h2>
            <form id="taskForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="block text-sm font-medium text-[#642667]">Titre :</label>
                <input type="text" name="title" id="taskTitle" class="border rounded w-full p-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-[#642667]">Description :</label>
                <textarea name="description" id="taskDescription" rows="3" class="border rounded w-full p-2"></textarea>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-[#642667]">Associer à un Epic :</label>
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
                <label class="block text-sm font-medium text-[#642667]" for="taskAssignee">Assigner à un associé :</label>
                <select name="assigned_to" id="taskAssignee" class="border rounded w-full p-2">
                    <option value="">-- Aucun --</option>
                    @foreach($associates as $user)
                        <option value="{{ $user->id_user }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-[#642667]" for="taskDueDate">Date d'échéance :</label>
                <input type="date" name="due_date" id="taskDueDate" class="border rounded w-full p-2" required>
            </div>
            <div class="flex justify-between mt-4">
                <button type="button" onclick="closeTaskModal()" class="bg-gray-400 text-white px-3 py-1 rounded">Fermer</button>
                <div id="actionButtons" class="flex gap-2"></div>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Script drag&drop (inchangé, sauf la gestion global "dragged") -->
<script>
let dragged = null;
window.dragged = false; // ASTUCE pour empêcher le clic pendant drag
document.querySelectorAll('.kanban-task').forEach(task => {
    if (task.hasAttribute('draggable') && task.getAttribute('draggable') === "true") {
        task.addEventListener('dragstart', e => {
            window.dragged = true;
            dragged = task;
            setTimeout(() => (task.style.display = 'none'), 0);
        });
        task.addEventListener('dragend', e => {
            window.dragged = false;
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
        this.insertBefore(dragged, this.firstChild);
        setTimeout(() => {
            document.querySelectorAll('.kanban-column').forEach(col => {
                const colStatus = col.getAttribute('data-status');
                const countBadge = document.getElementById(`count-${colStatus}`);
                if (countBadge) {
                    const taskCount = col.querySelectorAll('.kanban-task').length;
                    countBadge.textContent = `${taskCount} tâche${taskCount !== 1 ? 's' : ''}`;
                }
            });
        }, 40);
        const statusKey = this.getAttribute('data-status');
        const taskId = dragged.dataset.id;
        dragged.dataset.status = statusKey;
        fetch(`/kanban/tasks/${taskId}/move`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: statusKey })
        });
    });
});
function openTaskModal(taskDiv, currentUserId, currentRole) {
    if (window.dragged) return;
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
