@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-6 bg-[#19181f] border border-[#373755] rounded-2xl shadow-xl p-8">
    <h1 class="text-2xl font-bold mb-4 text-[#8EE6D7]">Modifier l'Epic : {!! html_entity_decode($epic->name) !!}</h1>

    <form action="{{ route('epics.update', $epic->id_epic) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold mb-1 text-[#BBBCE1]">Nom de l'Epic</label>
            <input type="text" name="name" value="{{ old('name', $epic->name) }}"
                   class="w-full bg-[#181826] border border-[#373755] rounded-xl p-2 text-[#E6E8F5] focus:ring-2 focus:ring-[#6D80EF] focus:outline-none">
            @error('name') <p class="text-[#DE2E4B] text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('projects.roadmap', $epic->project_id) }}"
               class="bg-[#C9A8FD] text-[#232332] px-4 py-2 rounded-xl font-semibold hover:bg-[#E0BFFD] transition"> Retour</a>
            <button type="submit"
                    class="bg-[#8EE6D7] text-[#232332] px-5 py-2 rounded-xl font-semibold hover:bg-[#A4F2E3] transition">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
