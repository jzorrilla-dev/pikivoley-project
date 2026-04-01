<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'PikiVoley') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */@layer theme{:root,:host{--font-sans:'Instrument Sans',ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--color-orange-500:oklch(.705 .213 47.604);--color-orange-600:oklch(.646 .222 41.116);--spacing:.25rem;--text-sm:.875rem;--text-lg:1.125rem;--text-4xl:2.25rem;--text-5xl:3rem;--radius-lg:.5rem;--radius-xl:.75rem}}@layer base{*,:after,:before,::backdrop{box-sizing:border-box;border:0 solid;margin:0;padding:0}html,:host{-webkit-text-size-adjust:100%;tab-size:4;line-height:1.5;font-family:var(--default-font-family,ui-sans-serif,system-ui,sans-serif);-webkit-tap-highlight-color:transparent}body{line-height:inherit}}@layer components;@layer utilities{.flex{display:flex}.grid{display:grid}.hidden{display:none}.inline-flex{display:inline-flex}.table{display:table}.h-screen{height:100vh}.w-full{width:100%}.max-w-7xl{max-width:80rem}.flex-1{flex:1}.flex-col{flex-direction:column}.items-center{align-items:center}.justify-center{justify-content:center}.justify-between{justify-content:space-between}.gap-2{gap:calc(var(--spacing)*2)}.gap-4{gap:calc(var(--spacing)*4)}.gap-8{gap:calc(var(--spacing)*8)}.gap-12{gap:calc(var(--spacing)*12)}.rounded-lg{border-radius:var(--radius-lg)}.rounded-xl{border-radius:var(--radius-xl)}.bg-orange-500{background-color:var(--color-orange-500)}.bg-orange-600{background-color:var(--color-orange-600)}.p-4{padding:calc(var(--spacing)*4)}.p-6{padding:calc(var(--spacing)*6)}.p-8{padding:calc(var(--spacing)*8)}.px-6{padding-inline:calc(var(--spacing)*6)}.px-8{padding-inline:calc(var(--spacing)*8)}.py-3{padding-block:calc(var(--spacing)*3)}.py-4{padding-block:calc(var(--spacing)*4)}.text-center{text-align:center}.text-sm{font-size:var(--text-sm)}.text-lg{font-size:var(--text-lg)}.text-4xl{font-size:var(--text-4xl)}.text-5xl{font-size:var(--text-5xl)}.font-medium{font-weight:500}.font-bold{font-weight:700}.text-white{color:#fff}.text-gray-600{color:#525252}.hover\:bg-orange-600:hover{background-color:var(--color-orange-600)}.hover\:opacity-90:hover{opacity:.9}.transition{transition-property:all;transition-timing-function:cubic-bezier(.4,0,.2,1);transition-duration:.15s}}
            </style>
        @endif
    </head>
    <body class="bg-gray-50 text-gray-800 font-sans">
        <div class="min-h-screen flex flex-col items-center justify-center p-6">
            <div class="max-w-7xl w-full">
                <!-- Logo and Title -->
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-xl bg-orange-500 text-white mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="3">
                            <circle cx="32" cy="32" r="28" fill="#FF6B35"/>
                            <path d="M32 8 L32 16 M32 48 L32 56 M8 32 L16 32 M48 32 L56 32" stroke="#FFFFFF" stroke-linecap="round"/>
                            <path d="M14 14 Q32 22 50 14" stroke="#FFFFFF" stroke-width="2" fill="none"/>
                            <path d="M14 50 Q32 42 50 50" stroke="#FFFFFF" stroke-width="2" fill="none"/>
                            <path d="M20 10 Q26 32 20 54" stroke="#FFFFFF" stroke-width="2" fill="none"/>
                            <path d="M44 10 Q38 32 44 54" stroke="#FFFFFF" stroke-width="2" fill="none"/>
                            <circle cx="32" cy="32" r="6" fill="#FFFFFF"/>
                            <circle cx="32" cy="32" r="3" fill="#FF6B35"/>
                        </svg>
                    </div>
                    <h1 class="text-5xl font-bold text-gray-800 mb-4">PikiVoley</h1>
                    <p class="text-lg text-gray-600">Organiza tus partidos de vóley de manera sencilla</p>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div class="bg-white p-6 rounded-xl text-center shadow-sm">
                        <div class="w-12 h-12 mx-auto mb-4 rounded-lg bg-orange-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Organiza Partidos</h3>
                        <p class="text-gray-600 text-sm">Crea partidos de vóley y configura las reglas según tus necesidades</p>
                    </div>

                    <div class="bg-white p-6 rounded-xl text-center shadow-sm">
                        <div class="w-12 h-12 mx-auto mb-4 rounded-lg bg-orange-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Invita Jugadores</h3>
                        <p class="text-gray-600 text-sm">Invita a tus amigos y forma equipos平衡ados</p>
                    </div>

                    <div class="bg-white p-6 rounded-xl text-center shadow-sm">
                        <div class="w-12 h-12 mx-auto mb-4 rounded-lg bg-orange-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Sistema de Reputación</h3>
                        <p class="text-gray-600 text-sm">Construye tu reputación como jugador fair play</p>
                    </div>
                </div>

                <!-- Auth Links -->
                <div class="flex justify-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex px-8 py-4 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex px-8 py-4 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition">
                            Iniciar Sesión
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex px-8 py-4 bg-white text-orange-500 border-2 border-orange-500 font-medium rounded-lg hover:bg-orange-50 transition">
                                Registrarse
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </body>
</html>
