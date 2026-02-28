@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('matches.index') }}" class="text-indigo-600 hover:underline">&larr; Volver</a>
            
            @if ($match->isEditable())
                <div class="flex gap-2">
                    <a href="{{ route('matches.edit', $match) }}" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Editar</a>
                    <form action="{{ route('matches.destroy', $match) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">Eliminar</button>
                    </form>
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="mb-4 px-4 py-2 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
                {{ session('warning') }}
            </div>
        @endif

        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $match->location }}</h1>
                        <p class="text-gray-500">{{ $match->scheduled_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 text-lg font-bold text-indigo-600">{{ number_format($match->bet_amount) }} ₲</span>
                        <span class="block mt-1 px-2 py-1 text-xs font-medium rounded
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

            <div class="px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Equipos</h2>
                <div class="grid grid-cols-2 gap-6">
                    @foreach ($match->teams as $team)
                        <div class="border rounded-lg p-4 {{ $team->side === 'local' ? 'border-blue-300 bg-blue-50' : 'border-orange-300 bg-orange-50' }}">
                            <h3 class="font-medium text-gray-700 mb-2">
                                {{ $team->side === 'local' ? '🏠 Local' : '✈️ Visitante' }}
                            </h3>
                            <p class="text-sm text-gray-600">Capitán: {{ $team->captain?->name ?? 'Sin asignar' }}</p>
                            <ul class="mt-2 space-y-1">
                                @forelse ($team->players as $player)
                                    <li class="text-sm flex justify-between">
                                        <span>{{ $player->user?->name }}</span>
                                        @if ($player->is_captain)
                                            <span class="text-xs bg-yellow-100 text-yellow-800 px-1 rounded">CAP</span>
                                        @endif
                                        @if (!$player->confirmed)
                                            <span class="text-xs text-gray-400">Pendiente</span>
                                        @endif
                                    </li>
                                @empty
                                    <li class="text-sm text-gray-400">Sin jugadores</li>
                                @endforelse
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($match->rule)
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Reglas</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Modalidad:</span>
                        <span class="font-medium">{{ $match->rule->modality }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Toques máx:</span>
                        <span class="font-medium">{{ $match->rule->max_touches }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Puntos:</span>
                        <span class="font-medium">{{ $match->rule->points_to_win }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Diferencia:</span>
                        <span class="font-medium">{{ $match->rule->win_difference }}</span>
                    </div>
                </div>
            </div>
            @endif

            @if ($match->scores->isNotEmpty())
            <div class="px-6 py-4 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Marcador</h2>
                <div class="flex justify-center gap-8">
                    <div class="text-center">
                        <span class="block text-sm text-gray-500">Local</span>
                        <span class="text-4xl font-bold text-blue-600">{{ $match->scores->firstWhere('team_side', 'local')?->points ?? 0 }}</span>
                    </div>
                    <div class="text-2xl font-bold text-gray-300 self-center">-</div>
                    <div class="text-center">
                        <span class="block text-sm text-gray-500">Visitante</span>
                        <span class="text-4xl font-bold text-orange-600">{{ $match->scores->firstWhere('team_side', 'visitante')?->points ?? 0 }}</span>
                    </div>
                </div>
            </div>
            @endif

            @if ($match->referee)
            <div class="px-6 py-4 border-t border-gray-200 bg-purple-50">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Árbitro</h2>
                <p class="text-gray-700">{{ $match->referee->name }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
