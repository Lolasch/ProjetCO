@extends('layouts.app')

@section('content')
<div class="w-full px-4 py-6">
    <div class="rounded-xl shadow-2xl p-8 mx-auto" style="background: rgba(20, 18, 50, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(177, 185, 234, 0.2); max-width: 900px;">
        <h1 class="text-2xl font-bold mb-6 text-center" style="color: #b1b9ea; font-family: 'Poppins', sans-serif;">Modifier la tâche</h1>

        <form action="{{ route('tasks.update', $task->id_task) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block font-bold mb-2" style="color: #d5dff5;">Titre :</label>
                <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required
                    class="w-full rounded-lg px-4 py-2 border transition focus:outline-none focus:ring-2"
                    style="background: rgba(177, 185, 234, 0.1); border-color: rgba(177, 185, 234, 0.3); color: #b1b9ea;">
            </div>

            <div>
                <label for="status" class="block font-bold mb-2" style="color: #d5dff5;">Statut :</label>
                <select name="status" id="status" class="w-full rounded-lg px-4 py-2 border transition focus:outline-none focus:ring-2"
                    style="background: rgba(177, 185, 234, 0.1); border-color: rgba(177, 185, 234, 0.3); color: #b1b9ea;">
                    <option value="todo" @if($task->status=='todo') selected @endif>À faire</option>
                    <option value="in_progress" @if($task->status=='in_progress') selected @endif>En cours</option>
                    <option value="done" @if($task->status=='done') selected @endif>Terminée</option>
                </select>
            </div>

            <div>
                <label for="assigned_to" class="block font-bold mb-2" style="color: #d5dff5;">Assigner à un associé :</label>
                <select name="assigned_to" id="assigned_to" class="w-full rounded-lg px-4 py-2 border transition focus:outline-none focus:ring-2"
                    style="background: rgba(177, 185, 234, 0.1); border-color: rgba(177, 185, 234, 0.3); color: #b1b9ea;">
                    <option value="">-- Aucun --</option>
                    @foreach($associates as $user)
                        <option value="{{ $user->id_user }}"
                            @if(old('assigned_to', $task->assigned_to) == $user->id_user) selected @endif>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="due_date" class="block font-bold mb-2" style="color: #d5dff5;">Date d'échéance :</label>
                <input type="date" name="due_date" id="due_date"
                       value="{{ old('due_date', $task->due_date) }}"
                       required class="w-full rounded-lg px-4 py-2 border transition focus:outline-none focus:ring-2"
                       style="background: rgba(177, 185, 234, 0.1); border-color: rgba(177, 185, 234, 0.3); color: #b1b9ea;">
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block font-bold mb-2" style="color: #d5dff5;">Description :</label>
                <textarea name="description" id="description" rows="2"
                    class="w-full rounded-lg px-4 py-2 border transition focus:outline-none focus:ring-2"
                    style="background: rgba(177, 185, 234, 0.1); border-color: rgba(177, 185, 234, 0.3); color: #b1b9ea;">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="w-full font-bold py-3 rounded-lg shadow-lg transition text-lg cursor-pointer"
                    style="background: #5b6cb2; color: white; border: none;"
                    onmouseover="this.style.background='#43519e';"
                    onmouseout="this.style.background='#5b6cb2';">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6 flex justify-start px-4" style="max-width: 900px; margin-left: auto; margin-right: auto;">
        <a href="{{ url()->previous() }}"
           style="background: rgba(177, 185, 234, 0.2); color: #b1b9ea; border: 1px solid rgba(177, 185, 234, 0.5); padding: 0.5rem 1.5rem; border-radius: 0.75rem; font-weight: 600; box-shadow: 0 4px 12px rgba(91, 108, 178, 0.18); transition: background 0.3s ease; cursor: pointer; text-decoration: none;"
           onmouseover="this.style.background='rgba(177, 185, 234, 0.3)'; this.style.borderColor='#b1b9ea';"
           onmouseout="this.style.background='rgba(177, 185, 234, 0.2)'; this.style.borderColor='rgba(177, 185, 234, 0.5)';">
            ← Retour
        </a>
    </div>
</div>
@endsection
