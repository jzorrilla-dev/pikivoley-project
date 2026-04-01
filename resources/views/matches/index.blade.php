@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mis Partidos</h1>
                <p class="text-sm text-gray-500 mt-1">Gestiona tus partidos de voleibol</p>
            </div>
            <a href="{{ route('matches.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Crear Partido
            </a>
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

        @if ($matches->isEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No hay partidos</h3>
                    <p class="mt-2 text-sm text-gray-500">Crea tu primer partido para empezar a jugar.</p>
                    <a href="{{ route('matches.create') }}" class="mt-6 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 transition">
                        Crear Partido
                    </a>
                </div>
            </div>
        @else
            <!-- Matches Grid -->
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($matches as $match)
                    <a href="{{ route('matches.show', $match) }}" class="group bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:border-indigo-300 hover:shadow-md transition-all duration-200">
                        <!-- Card Header -->
                        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($match->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($match->status === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($match->status === 'in_progress') bg-green-100 text-green-800
                                @elseif($match->status === 'finished') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($match->status) }}
                            </span>
                            <span class="text-lg font-bold text-indigo-600">{{ number_format($match->bet_amount) }} ₲</span>
                        </div>
                        <!-- Card Body -->
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition">{{ $match->location }}</h3>
                            <div class="mt-3 space-y-2">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $match->scheduled_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ $match->rule?->modality ?? 'Sin modalidad' }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    {{ $match->teams->count() }} equipos
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $matches->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
