@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('matches.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a Partidos
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Crear Nuevo Partido</h1>
            <p class="text-sm text-gray-500 mt-1">Completa los detalles para organizar un nuevo partido de voleibol</p>
        </div>

        <!-- Form -->
        <form action="{{ route('matches.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6 space-y-6">
                <!-- Match Details -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Detalles del Partido
                    </h2>
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Ubicación</label>
                    <input type="text" name="location" id="location" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3"
                        placeholder="Nombre del lugar o dirección">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700">Fecha y Hora</label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3">
                    @error('scheduled_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bet_amount" class="block text-sm font-medium text-gray-700">Apuesta (Guaraníes)</label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₲</span>
                        </div>
                        <input type="number" name="bet_amount" id="bet_amount" required min="0"
                            class="block w-full pl-8 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3"
                            placeholder="0">
                    </div>
                    @error('bet_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Rules -->
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Reglas del Partido
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="modality" class="block text-sm font-medium text-gray-700">Modalidad</label>
                        <select name="modality" id="modality" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3">
                            <option value="1x1">1 vs 1</option>
                            <option value="2x2">2 vs 2</option>
                            <option value="3x3" selected>3 vs 3</option>
                        </select>
                        @error('modality')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_touches" class="block text-sm font-medium text-gray-700">Toques máximos</label>
                        <select name="max_touches" id="max_touches" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3">
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
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3">
                            <option value="foot_only" selected>Solo pie</option>
                            <option value="hand_allowed">Mano permitida</option>
                        </select>
                        @error('serve_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="points_to_win" class="block text-sm font-medium text-gray-700">Puntos para ganar</label>
                        <input type="number" name="points_to_win" id="points_to_win" required min="1" value="12"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3">
                        @error('points_to_win')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="win_difference" class="block text-sm font-medium text-gray-700">Diferencia mínima</label>
                        <input type="number" name="win_difference" id="win_difference" required min="1" value="2"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3">
                        @error('win_difference')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-4">
                <button type="submit"
                    class="flex-1 flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Crear Partido
                </button>
                <a href="{{ route('matches.index') }}"
                    class="flex-1 flex justify-center items-center px-4 py-3 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
