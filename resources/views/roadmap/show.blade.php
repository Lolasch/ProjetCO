@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <h1 class="text-2xl font-bold mb-4">
        Roadmap : {{ $project->name }}
    </h1>

    <!-- Bouton pour ajouter un sprint -->
    <a href="{{ route('sprints.create', $project->id_project) }}"
       class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
       + Ajouter un sprint
    </a>

    <!-- Conteneur du calendrier -->
    <div id="calendar" class="mt-6 border rounded-lg shadow p-4"></div>
</div>

<!-- ✅ FullCalendar CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        locale: 'fr', // en français 🇫🇷
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
                title: '{{ $sprint->name }}',
                start: '{{ $sprint->start_date }}',
                end: '{{ $sprint->end_date }}',
                color: '#1e40af', // bleu pour les sprints
                extendedProps: {
                    epics: [
                        @foreach($sprint->epics as $epic)
                        {
                            title: '{{ $epic->name }}',
                            assignee: '{{ $epic->assignee ?? "Non défini" }}'
                        },
                        @endforeach
                    ]
                }
            },
            @endforeach
        ],
        eventClick: function(info) {
            const epics = info.event.extendedProps.epics;
            if (epics && epics.length > 0) {
                let list = epics.map(e => `${e.title} (${e.assignee})`).join("\n");
                alert("Épics dans ce sprint :\n\n" + list);
            } else {
                alert("Aucun epic dans ce sprint.");
            }
        }
    });

    calendar.render();
});
</script>
@endsection
