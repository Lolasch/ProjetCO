@extends('layouts.app')

@section('title', 'Liste des tâches')

@section('content')
<h2>Liste des tâches du sprint : {{ $sprint->name }}</h2>

<a href="{{ route('tasks.create', $sprint->id_sprint) }}">➕ Ajouter une tâche</a>

<ul>
    @foreach ($tasks as $task)
        <li>
            <strong>{{ $task->title }}</strong> ({{ $task->status }})
            <p>{{ $task->description }}</p>
        </li>
    @endforeach
</ul>
@endsection
