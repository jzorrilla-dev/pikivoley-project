@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Mis Partidos</h1>
            <a href="{{ route('matches.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Crear Partido
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($matches->isEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                No tienes partidos creados todavía.
                <a href="{{ route('matches.create') }}" class="text-indigo-600 hover:underline">Crea el primero</a>
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($matches as $match)
                    <a href="{{ route('matches.show', $match) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-2 py-1 text-xs font-medium rounded
                                @if($match->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($match->status === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($match->status === 'in_progress') bg-green-100 text-green-800
                                @elseif($match->status === 'finished') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($match->status) }}
                            </span>
                            <span class="text-lg font-bold text-indigo-600">{{ number_format($match->bet_amount) }} ₲</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $match->location }}</h3>
                        <p class="text-sm text-gray-500 mb-2">{{ $match->scheduled_at->format('d/m/Y H:i') }}</p>
                        <p class="text-sm text-gray-600">{{ $match->rule?->modality }}</p>
                    </a>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $matches->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
