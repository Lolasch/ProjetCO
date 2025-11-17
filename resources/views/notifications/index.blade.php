@extends('layouts.app')

@section('content')
<div class="px-8 py-6 max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #b1b9ea; font-family: 'Poppins', sans-serif;">Mes Notifications</h1>

    @if($notifications->isEmpty())
        <div class="bg-[#222133] p-6 rounded text-center" style="color: #b1b9ea;">
            Aucune notification pour le moment
        </div>
    @else
        <div class="space-y-4">
            @foreach($notifications as $notification)
                {{-- Afficher uniquement les notifications non lues --}}
                @if(!$notification->is_read)
                    @if($notification->task_id)
                        <a href="{{ route('tasks.edit', $notification->task_id) }}"
                            class="block border rounded-lg p-4 transition hover:shadow-lg"
                            style="background: rgba(80, 80, 110, 0.7); border-color: #5b6cb2; color: #b1b9ea; text-decoration: none;">
                    @else
                        <div class="border rounded-lg p-4"
                            style="background: rgba(80, 80, 110, 0.7); border-color: #5b6cb2; color: #b1b9ea;">
                    @endif
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    @if($notification->type == 'deadline')
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-[#F47B7B] text-[#461515]">
                                            Retard
                                        </span>
                                    @elseif($notification->type == 'update')
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-[#78A6F4] text-[#1A2F5B]">
                                            Mise à jour
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-[#777a8a] text-[#dcdde6]">
                                            {{ ucfirst($notification->type) }}
                                        </span>
                                    @endif
                                    <span class="text-xs" style="color: #b1b9ea;">
                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-lg" style="color: #d5dff5;">{{ $notification->title }}</h3>
                                @if($notification->body)
                                    <p style="color:#ccc; margin-top: 0.25rem;">{{ $notification->body }}</p>
                                @endif
                            </div>
                            <div class="ml-4 flex items-center" onclick="event.stopPropagation();">
                                <form action="{{ route('notifications.read', $notification->id_notification) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            style="padding: 0.25rem 0.75rem; background: #2F855A; color: white; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600; transition: background 0.3s ease; cursor: pointer;"
                                            onmouseover="this.style.background='#276749';"
                                            onmouseout="this.style.background='#2F855A';">
                                        Marquer comme lu
                                    </button>
                                </form>
                            </div>
                        </div>
                    @if($notification->task_id)
                        </a>
                    @else
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
    @endif

    <div class="mt-8 flex justify-start px-2">
        <a href="{{ url()->previous() }}"
           style="background: rgba(177, 185, 234, 0.2); color: #b1b9ea; border: 1px solid rgba(177, 185, 234, 0.5); padding: 0.5rem 1.5rem; border-radius: 0.75rem; font-weight: 600; box-shadow: 0 4px 12px rgba(91, 108, 178, 0.18); transition: background 0.3s ease; cursor: pointer; text-decoration: none;"
           onmouseover="this.style.background='rgba(177, 185, 234, 0.3)'; this.style.borderColor='#b1b9ea';"
           onmouseout="this.style.background='rgba(177, 185, 234, 0.2)'; this.style.borderColor='rgba(177, 185, 234, 0.5)';">
            ← Retour
        </a>
    </div>
</div>
@endsection
