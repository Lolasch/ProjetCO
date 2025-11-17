@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center py-10 w-full">

    {{-- HEADER RETOUR + SPRINT SELECT --}}
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
                            <option value="" disabled @unless($sprint) selected @endunless hidden>Visualiser un autre sprint</option>
                            @foreach($sprints as $s)
                                <option value="{{ $s->id_sprint }}"
                                    @if($sprint && $s->id_sprint == $sprint->id_sprint) selected @endif>
                                    {{ $s->name }} ({{ $s->start_date }} → {{ $s->end_date }})
                                </option>
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

    {{-- BARRE DE FILTRAGE --}}
    @php
        $hasActiveFilters = $filters['keyword'] || $filters['assignee'] || $filters['due_date'] || $filters['status'];
    @endphp

    <div class="w-full max-w-[1400px] mb-6">
        <!-- Bouton Filtrer + Filtres actifs -->
        <div class="flex items-center gap-3 mb-3">
            <button type="button" onclick="toggleFilterPanel()"
                    class="flex items-center gap-2 bg-[#9066ad] hover:bg-[#7a4f8e] text-white px-4 py-2 rounded-lg font-medium transition text-sm"
                    id="filterToggleBtn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filtrer
            </button>

            <!-- Affichage des filtres actifs (badges) -->
            @if($hasActiveFilters)
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="text-xs text-[#ba78e8] font-semibold">Actifs :</span>

                    @if($filters['keyword'])
                        <span class="text-xs bg-[#9066ad] text-white px-3 py-1.5 rounded-full">"{{ $filters['keyword'] }}"</span>
                    @endif

                    @if($filters['assignee'])
                        @php
                            $assigneeUser = $associates->find($filters['assignee']);
                        @endphp
                        <span class="text-xs bg-[#9066ad] text-white px-3 py-1.5 rounded-full">{{ $assigneeUser->name ?? 'Inconnu' }}</span>
                    @endif

                    @if($filters['due_date'])
                        <span class="text-xs bg-[#9066ad] text-white px-3 py-1.5 rounded-full">avant {{ $filters['due_date'] }}</span>
                    @endif

                    @if($filters['status'])
                        @php
                            $statusLabels = ['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé'];
                        @endphp
                        <span class="text-xs bg-[#9066ad] text-white px-3 py-1.5 rounded-full">{{ $statusLabels[$filters['status']] }}</span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Panneau de filtrage (hidden par défaut) -->
        <form method="GET" action="{{ route('projects.kanban', $project->id_project) }}" class="bg-[#24202a] border border-[#4b4155] rounded-lg p-4 hidden" id="filterPanel">

            <!-- Garder le sprint sélectionné -->
            @if($sprint)
                <input type="hidden" name="sprint" value="{{ $sprint->id_sprint }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">

                <!-- Recherche par mot-clé -->
                <div>
                    <label class="block text-xs font-semibold text-[#ba78e8] mb-1.5">Rechercher</label>
                    <input type="text"
                           name="keyword"
                           placeholder="Titre, description..."
                           value="{{ old('keyword', $filters['keyword'] ?? '') }}"
                           class="w-full border border-[#4b4155] rounded-lg p-2 bg-[#1b1a20] text-white text-sm
                                  focus:border-[#9066ad] focus:ring-1 focus:ring-[#9066ad] outline-none transition">
                </div>

                <!-- Filtrer par responsable -->
                <div>
                    <label class="block text-xs font-semibold text-[#ba78e8] mb-1.5">Responsable</label>
                    <select name="assignee"
                            class="w-full border border-[#4b4155] rounded-lg p-2 bg-[#24202a] text-white text-sm font-medium
                                   focus:border-[#9066ad] focus:ring-1 focus:ring-[#9066ad] outline-none transition">
                        <option value="">Responsable</option>
                        @foreach($associates as $user)
                            <option value="{{ $user->id_user }}"
                                @if($filters['assignee'] == $user->id_user) selected @endif>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtrer par date d'échéance -->
                <div>
                    <label class="block text-xs font-semibold text-[#ba78e8] mb-1.5">Avant le</label>
                    <input type="date"
                           name="due_date"
                           value="{{ old('due_date', $filters['due_date'] ?? '') }}"
                           class="w-full border border-[#4b4155] rounded-lg p-2 bg-[#1b1a20] text-white text-sm
                                  focus:border-[#9066ad] focus:ring-1 focus:ring-[#9066ad] outline-none transition">
                </div>

                <!-- Filtrer par statut -->
                <div>
                    <label class="block text-xs font-semibold text-[#ba78e8] mb-1.5">Statut</label>
                    <select name="status"
                            class="w-full border border-[#4b4155] rounded-lg p-2 bg-[#24202a] text-white text-sm font-medium
                                   focus:border-[#9066ad] focus:ring-1 focus:ring-[#9066ad] outline-none transition">
                        <option value="">Statut</option>
                        <option value="todo" @if($filters['status'] == 'todo') selected @endif>À faire</option>
                        <option value="in_progress" @if($filters['status'] == 'in_progress') selected @endif>En cours</option>
                        <option value="done" @if($filters['status'] == 'done') selected @endif>Terminé</option>
                    </select>
                </div>

                <!-- Boutons Appliquer et Réinitialiser -->
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 bg-[#8fc8b2] hover:bg-[#4e9893] text-[#19181b] px-4 py-2 rounded-lg font-medium transition text-sm">
                        Appliquer
                    </button>
                    <a href="{{ route('projects.kanban', $project->id_project) }}"
                       class="flex-1 bg-[#9066ad] hover:bg-[#7a4f8e] text-white px-4 py-2 rounded-lg font-medium transition text-sm text-center">
                        Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- COLONNES KANBAN --}}
    <main class="w-full max-w-[1400px] flex flex-col lg:flex-row space-y-8 lg:space-y-0 lg:space-x-8 justify-center items-start">
        @foreach(['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé'] as $statusKey => $statusLabel)
            <section class="bg-[#19181b] rounded-xl scrollbar-noir px-5 py-8 w-full max-w-[400px] shadow-xl flex flex-col" style="height: 80vh;">
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

                            // Récupère la personne assignée
                            $assignedUser = null;
                            if ($task->assigned_to) {
                                $assignedUser = $associates->find($task->assigned_to);
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
                            <!-- En-tête: titre + epic -->
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <span class="font-bold text-base text-[#ebeafd] break-words flex-1">{{ $task->title }}</span>
                                @if($task->epic)
                                    <span class="bg-[#ba78e8] text-white text-xs rounded-full px-3 py-1 whitespace-nowrap">
                                        {{ $task->epic->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Date d'échéance -->
                            <span class="text-xs text-[#bac8df] mb-3">
                                Échéance : {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                            </span>

                            <!-- Alerte si nécessaire -->
                            @if($alerte)
                                <div class="flex items-center justify-start mb-3">
                                    <span class="inline-flex items-center px-3 py-1 {{ $alerte_color }} text-xs font-semibold rounded-lg">
                                        {{ $alerte }}
                                    </span>
                                </div>
                            @endif

                            <!-- Avatar de la personne assignée -->
                            @if($assignedUser)
                                <div class="flex items-center gap-2 mt-2 pt-2 border-t border-[#4b4155]">
                                    <div class="w-7 h-7 rounded-full bg-[#023e8a] text-white text-xs font-bold flex items-center justify-center">
                                        {{ strtoupper(substr($assignedUser->name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs text-[#e2e9f8] font-medium">{{ $assignedUser->name }}</span>
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

    {{-- POPUP MODIFICATION TÂCHE --}}
    <div id="taskModal"
         class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur-md bg-transparent transition-all duration-200 hidden">
      <div class="bg-[#19181b] rounded-xl shadow-2xl w-[480px] max-w-[95vw] min-h-[320px] max-h-[80vh]
                  p-8 relative border border-[#24202a] flex flex-col">
        <h2 class="text-xl font-bold mb-4 text-white">Modifier la tâche</h2>

        <form id="taskForm" method="POST" class="flex flex-col gap-4 flex-1 overflow-y-auto pr-1 scrollbar-noir">
          @csrf
          @method('PUT')

          <div>
            <label class="block text-sm font-semibold text-[#ba78e8] mb-1">Titre :</label>
            <input type="text" name="title" id="taskTitle"
                   class="border border-[#4b4155] rounded-lg w-full p-2 bg-[#1b1a20] text-white
                          focus:border-[#9066ad] focus:ring-1 focus:ring-[#9066ad] outline-none"
                   required>
          </div>
          <div>
            <label class="block text-sm font-semibold text-[#ba78e8] mb-1">Description :</label>
            <textarea name="description" id="taskDescription" rows="2"
                      class="border border-[#4b4155] rounded-lg w-full p-2 bg-[#1b1a20] text-white
                             focus:border-[#9066ad] focus:ring-1 focus:ring-[#9066ad] outline-none"></textarea>
          </div>
          <div>
            <label class="block text-sm font-semibold text-[#ba78e8] mb-1">Associer à un Epic :</label>
            <select name="epic_id" id="taskEpic"
                    class="border border-[#4b4155] rounded-lg w-full p-2 bg-[#24202a] text-white font-medium">
              <option value="">Aucun</option>
              @if(isset($sprint->epics))
                @foreach($sprint->epics as $epic)
                  <option value="{{ $epic->id_epic }}">{{ $epic->name }}</option>
                @endforeach
              @endif
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-[#ba78e8] mb-1">Assigner à un associé :</label>
            <select name="assigned_to" id="taskAssignee"
                    class="border border-[#4b4155] rounded-lg w-full p-2 bg-[#24202a] text-white font-medium">
              <option value="">-- Aucun --</option>
              @foreach($associates as $user)
                <option value="{{ $user->id_user }}">{{ $user->name }} ({{ $user->email }})</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-[#ba78e8] mb-1">Date d'échéance :</label>
            <input type="date" name="due_date" id="taskDueDate"
                   class="border border-[#4b4155] rounded-lg w-full p-2 bg-[#1b1a20] text-white
                          focus:border-[#8fc8b2] focus:ring-1 focus:ring-[#8fc8b2] outline-none"
                   required>
          </div>
        </form>

        <div class="flex flex-wrap justify-between mt-4 gap-2">
          <button type="button"
                  onclick="closeTaskModal()"
                  class="bg-[#ba78e8] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#9066ad] transition">
            Fermer
          </button>

          <button type="submit" form="taskForm" id="saveButton"
                  class="bg-[#8fc8b2] text-[#19181b] px-4 py-2 rounded-lg font-medium hover:bg-[#4e9893] transition hidden">
            Enregistrer
          </button>

          <button type="button" id="deleteButton"
                  class="bg-[#e38b99] text-white px-4 py-2 rounded-lg font-medium hover:bg-[#c1546b] transition hidden">
            Supprimer
          </button>
        </div>
      </div>
    </div>

    {{-- FORMULAIRE CACHÉ POUR LA SUPPRESSION --}}
    <form id="deleteTaskForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
</div>

{{-- SCRIPT DRAG & DROP + MODAL + TOGGLE FILTRE --}}
<script>
    let dragged = null;
    window.dragged = false;

    // Toggle du panneau de filtrage
    function toggleFilterPanel() {
        const panel = document.getElementById('filterPanel');
        panel.classList.toggle('hidden');
    }

    // Affiche le panel de filtre si des filtres sont actifs au chargement
    @if($hasActiveFilters)
        document.getElementById('filterPanel').classList.remove('hidden');
    @endif

    // Drag & drop seulement si draggables (manager ou associé propriétaire)
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

            // Mise à jour des compteurs
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

        const modal    = document.getElementById('taskModal');
        const form     = document.getElementById('taskForm');
        const title    = document.getElementById('taskTitle');
        const desc     = document.getElementById('taskDescription');
        const epic     = document.getElementById('taskEpic');
        const assignee = document.getElementById('taskAssignee');
        const dueDate  = document.getElementById('taskDueDate');
        const saveBtn  = document.getElementById('saveButton');
        const deleteBtn= document.getElementById('deleteButton');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        title.value    = taskDiv.dataset.title;
        desc.value     = taskDiv.dataset.description;
        epic.value     = taskDiv.dataset.epic || '';
        assignee.value = taskDiv.dataset.assigned_to || '';
        dueDate.value  = taskDiv.dataset.due_date || '';

        const taskId     = taskDiv.dataset.id;
        const assignedTo = taskDiv.dataset.assigned_to;
        form.action      = `/tasks/${taskId}`;

        title.disabled    = true;
        desc.disabled     = true;
        epic.disabled     = true;
        assignee.disabled = true;
        dueDate.disabled  = true;

        saveBtn.classList.add('hidden');
        saveBtn.disabled = true;

        deleteBtn.classList.add('hidden');
        deleteBtn.onclick = null;

        if (currentRole === 'manager') {
            title.disabled    = false;
            desc.disabled     = false;
            epic.disabled     = false;
            assignee.disabled = false;
            dueDate.disabled  = false;

            saveBtn.classList.remove('hidden');
            saveBtn.disabled = false;

            deleteBtn.classList.remove('hidden');
            deleteBtn.onclick = () => deleteTask(taskId);
        }
        else if (currentRole === 'associate' && String(currentUserId) === String(assignedTo)) {
            title.disabled    = false;
            desc.disabled     = false;
            epic.disabled     = false;
            assignee.disabled = true;
            dueDate.disabled  = false;

            saveBtn.classList.remove('hidden');
            saveBtn.disabled = false;

            deleteBtn.classList.remove('hidden');
            deleteBtn.onclick = () => deleteTask(taskId);
        }
    }

    function closeTaskModal() {
        const modal = document.getElementById('taskModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function deleteTask(id) {
        if (confirm('Supprimer cette tâche ?')) {
            const form = document.getElementById('deleteTaskForm');
            form.action = `/tasks/${id}`;
            form.submit();
        }
    }
</script>
@endsection
