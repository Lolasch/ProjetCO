@extends('layouts.app')

@section('content')
<div class="px-8 py-6 max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Mes Notifications</h1>

    @if($notifications->isEmpty())
        <div class="bg-gray-100 p-6 rounded text-center text-gray-500">
            Aucune notification pour le moment
        </div>
    @else
        <div class="space-y-3">
            @foreach($notifications as $notification)
                <div class="border rounded-lg p-4 {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                @if($notification->type == 'deadline')
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
                                        Échéance
                                    </span>
                                @elseif($notification->type == 'update')
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                        Mise à jour
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">
                                        {{ ucfirst($notification->type) }}
                                    </span>
                                @endif
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </span>
                            </div>
                            <h3 class="font-bold text-lg">{{ $notification->title }}</h3>
                            @if($notification->body)
                                <p class="text-gray-700 mt-1">{{ $notification->body }}</p>
                            @endif
                            @if($notification->task_id)
                                <a href="{{ route('tasks.edit', $notification->task_id) }}"
                                   class="text-blue-600 hover:underline text-sm mt-2 inline-block">
                                    Voir la tâche →
                                </a>
                            @endif
                        </div>
                        <div class="ml-4">
                            @if(!$notification->is_read)
                                <form action="{{ route('notifications.read', $notification->id_notification) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600">
                                        Marquer comme lu
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">✓ Lu</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
