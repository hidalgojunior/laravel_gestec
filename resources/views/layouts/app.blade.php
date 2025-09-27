<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GESTEC') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased {{ $currentTheme === 'dark' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-900' }}">
        @if(Auth::check())
            <!-- Authenticated Layout with Sidebar -->
            <div class="min-h-screen {{ $currentTheme === 'dark' ? 'bg-gray-900' : 'bg-gray-100' }}">
                <!-- Header -->
                <header class="{{ $currentTheme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white' }} shadow-sm border-b">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between items-center py-4">
                            <div class="flex items-center">
                                <img src="{{ asset('images/logo.svg') }}" alt="GESTEC Logo" class="h-10 w-auto" />
                                <h1 class="ml-3 text-xl font-semibold {{ $currentTheme === 'dark' ? 'text-white' : 'text-gray-900' }}">GESTEC</h1>
                            </div>
                            <div class="text-sm {{ $currentTheme === 'dark' ? 'text-gray-300' : 'text-gray-600' }}">
                                Bem-vindo, {{ Auth::user()->name }}!
                            </div>
                        </div>
                    </div>
                </header>

                <div class="flex">
                    <!-- Sidebar -->
                    <aside class="w-64 {{ $currentTheme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white' }} shadow-sm min-h-screen border-r">
                        <nav class="mt-8">
                            <div class="px-4 space-y-2">
                                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium {{ $currentTheme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg {{ request()->routeIs('dashboard') ? ($currentTheme === 'dark' ? 'bg-gray-700 text-white' : 'bg-blue-50 text-blue-700') : '' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                    </svg>
                                    Dashboard
                                </a>

                                @if(Auth::user()->isAdmin())
                                <div class="space-y-1">
                                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Administração
                                    </div>
                                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        Gerenciar Usuários
                                    </a>
                                    <a href="{{ route('events.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('events.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Eventos
                                    </a>
                                    <a href="{{ route('activities.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('activities.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        Atividades
                                    </a>
                                    <a href="{{ route('enrollments.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('enrollments.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Inscrições
                                    </a>
                                    <a href="{{ route('presences.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('presences.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Presenças
                                    </a>
                                    <a href="{{ route('certificates.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('certificates.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Certificados
                                    </a>
                                    <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('admin.settings*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Configurações
                                    </a>
                                </div>
                                @endif

                                <div class="pt-4 space-y-1">
                                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Conta
                                    </div>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Perfil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </nav>
                    </aside>

                    <!-- Main Content -->
                    <main class="flex-1 p-8">
                        @yield('content')
                    </main>
                </div>
            </div>
        @else
            <!-- Public Layout -->
            <div class="min-h-screen bg-gray-100">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    @yield('content')
                </main>
            </div>
        @endif

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-sm text-gray-500">
                    © {{ date('Y') }} GESTEC. Todos os direitos reservados.
                </div>
            </div>
        </footer>

        @yield('scripts')
    </body>
</html>
