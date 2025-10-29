@extends('layouts.app')

@section('content')
<h1>Créer un Epic pour le sprint : {{ $sprint->name }}</h1>

<form action="{{ route('epics.store', $sprint->id_sprint) }}" method="POST">
    @csrf
    <label>Nom de l'Epic :</label><br>
    <input type="text" name="name" required><br><br>

    <button type="submit">Créer l'Epic</button>
</form>
@endsection
