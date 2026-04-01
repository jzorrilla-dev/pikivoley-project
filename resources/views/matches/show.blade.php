@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-6 gap-4">
            <a href="{{ route('matches.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a Partidos
            </a>
            
            @if ($match->isEditable())
                <div class="flex gap-2">
                    <a href="{{ route('matches.edit', $match) }}" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                    <form action="{{ route('matches.destroy', $match) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este partido?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="mb-6 px-4 py-3 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                {{ session('warning') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Match Card -->
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
            <!-- Match Header -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $match->location }}</h1>
                        <div class="flex items-center mt-2 text-gray-500">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $match->scheduled_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="text-left sm:text-right">
                        <div class="text-2xl font-bold text-indigo-600">{{ number_format($match->bet_amount) }} ₲</div>
                        <span class="inline-flex items-center mt-2 px-3 py-1 rounded-full text-xs font-medium
                            @if($match->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($match->status === 'confirmed') bg-blue-100 text-blue-800
                            @elseif($match->status === 'in_progress') bg-green-100 text-green-800
                            @elseif($match->status === 'finished') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($match->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Teams -->
            <div class="px-6 py-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Equipos
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse ($match->teams as $team)
                        <div class="rounded-xl p-4 border-2 transition
                            {{ $team->side === 'local' ? 'border-blue-200 bg-blue-50/50' : 'border-orange-200 bg-orange-50/50' }}">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-gray-900 flex items-center">
                                    @if($team->side === 'local')
                                        <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-2 text-sm">L</span>
                                    @else
                                        <span class="w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mr-2 text-sm">V</span>
                                    @endif
                                    {{ ucfirst($team->side) }}
                                </h3>
                                @if($match->winner_team_id === $team->id)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Ganador</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Capitán</p>
                                <p class="text-sm font-medium text-gray-900">{{ $team->captain?->name ?? 'Sin asignar' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Jugadores</p>
                                @forelse ($team->players as $player)
                                    <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-white/50">
                                        <span class="text-sm text-gray-700 flex items-center">
                                            {{ $player->user?->name }}
                                            @if ($player->is_captain)
                                                <span class="ml-2 px-1.5 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded">CAP</span>
                                            @endif
                                        </span>
                                        @if ($player->confirmed)
                                            <span class="inline-flex items-center text-xs text-green-600">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                Confirmado
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">Pendiente</span>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-400 italic">Sin jugadores</p>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-8 text-gray-500">
                            No hay equipos configurados
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Rules -->
            @if ($match->rule)
            <div class="px-6 py-5 border-t border-gray-200 bg-gray-50/50">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Reglas del Partido
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg p-3 border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase">Modalidad</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $match->rule->modality }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase">Toques máx</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $match->rule->max_touches }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase">Puntos</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $match->rule->points_to_win }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase">Diferencia</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $match->rule->win_difference }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Score -->
            @if ($match->scores->isNotEmpty())
            <div class="px-6 py-5 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Marcador
                </h2>
                <div class="flex justify-center items-center gap-6 sm:gap-12">
                    <div class="text-center">
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Local</span>
                        <span class="text-5xl font-bold text-blue-600">{{ $match->scores->firstWhere('team_side', 'local')?->points ?? 0 }}</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-200">-</div>
                    <div class="text-center">
                        <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Visitante</span>
                        <span class="text-5xl font-bold text-orange-600">{{ $match->scores->firstWhere('team_side', 'visitante')?->points ?? 0 }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Referee -->
            @if ($match->referee)
            <div class="px-6 py-4 border-t border-gray-200 bg-purple-50/50">
                <h2 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Árbitros
                </h2>
                <p class="text-gray-700 flex items-center">
                    <span class="w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mr-2 text-sm font-medium">
                        {{ substr($match->referee->name, 0, 1) }}
                    </span>
                    {{ $match->referee->name }}
                </p>
            </div>
            @endif

            <!-- Actions -->
            @if(in_array($match->status, ['pending', 'confirmed']))
            <div class="px-6 py-5 border-t border-gray-200 bg-gray-50/50">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Acciones</h2>
                <div class="flex flex-wrap gap-3">
                    @if($match->status === 'pending')
                        <form action="{{ route('matches.confirm', $match) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Confirmar Partido
                            </button>
                        </form>
                    @endif

                    @if($match->status === 'confirmed')
                        <form action="{{ route('matches.start', $match) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Iniciar Partido
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
