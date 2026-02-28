@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Crear Nuevo Partido</h1>

        <form action="{{ route('matches.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Ubicación</label>
                <input type="text" name="location" id="location" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Nombre del lugar o dirección">
                @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="scheduled_at" class="block text-sm font-medium text-gray-700">Fecha y Hora</label>
                <input type="datetime-local" name="scheduled_at" id="scheduled_at" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('scheduled_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="bet_amount" class="block text-sm font-medium text-gray-700">Apuesta (Guaraníes)</label>
                <input type="number" name="bet_amount" id="bet_amount" required min="0"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="0">
                @error('bet_amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <hr class="my-6">

            <h2 class="text-lg font-medium text-gray-900">Reglas del Partido</h2>

            <div>
                <label for="modality" class="block text-sm font-medium text-gray-700">Modalidad</label>
                <select name="modality" id="modality" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="1x1">1x1</option>
                    <option value="2x2">2x2</option>
                    <option value="3x3">3x3</option>
                </select>
                @error('modality')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="max_touches" class="block text-sm font-medium text-gray-700">Toques máximos</label>
                <select name="max_touches" id="max_touches" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="2">2 toques</option>
                    <option value="3" selected>3 toques</option>
                </select>
                @error('max_touches')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="serve_type" class="block text-sm font-medium text-gray-700">Tipo de saque</label>
                <select name="serve_type" id="serve_type" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="foot_only">Solo pie</option>
                    <option value="hand_allowed">Mano permitida</option>
                </select>
                @error('serve_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="points_to_win" class="block text-sm font-medium text-gray-700">Puntos para ganar</label>
                    <input type="number" name="points_to_win" id="points_to_win" required min="1" value="12"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('points_to_win')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="win_difference" class="block text-sm font-medium text-gray-700">Diferencia mínima</label>
                    <input type="number" name="win_difference" id="win_difference" required min="1" value="2"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('win_difference')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Crear Partido
                </button>
                <a href="{{ route('matches.index') }}"
                    class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-center rounded-md hover:bg-gray-400">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
