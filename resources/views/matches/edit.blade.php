@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Editar Partido</h1>

        <form action="{{ route('matches.update', $match) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Ubicación</label>
                <input type="text" name="location" id="location" required
                    value="{{ old('location', $match->location) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="scheduled_at" class="block text-sm font-medium text-gray-700">Fecha y Hora</label>
                <input type="datetime-local" name="scheduled_at" id="scheduled_at" required
                    value="{{ old('scheduled_at', $match->scheduled_at->format('Y-m-d\TH:i')) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('scheduled_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="bet_amount" class="block text-sm font-medium text-gray-700">Apuesta (Guaraníes)</label>
                <input type="number" name="bet_amount" id="bet_amount" required min="0"
                    value="{{ old('bet_amount', $match->bet_amount) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('bet_amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Guardar Cambios
                </button>
                <a href="{{ route('matches.show', $match) }}"
                    class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-center rounded-md hover:bg-gray-400">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
