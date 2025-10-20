@extends('layouts.app')

@section('content')
<h1>Tableau de bord</h1>

<a href="{{ route('projects.create') }}">Créer un projet</a>

@php
    $roles = ['manager' => [], 'employee' => [], 'client' => []];
@endphp

@foreach($projects as $project)
    @php
        $role = $project->pivot->role;
        $roles[$role][] = $project;
    @endphp
@endforeach

@foreach($roles as $role => $projList)
    <div style="border:1px solid #000; padding:10px; margin:10px;">
        <h2>{{ ucfirst($role) }}</h2>
        @if(count($projList) === 0)
            <p>Aucun projet.</p>
        @else
            <ul>
                @foreach($projList as $proj)
                    <li>
                        {{ $proj->name }}
                        @if($role === 'manager')
                            <a href="{{ route('projects.edit', $proj->id_project) }}">Modifier</a>
                            <form action="{{ route('projects.destroy', $proj->id_project) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Supprimer</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endforeach
@endsection
