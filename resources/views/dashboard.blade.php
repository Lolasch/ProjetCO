@extends('layouts.app')

@section('content')
<h1>Tableau de bord</h1>

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
                    <li>{{ $proj->name }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endforeach
@endsection
