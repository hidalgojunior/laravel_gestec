@extends('layouts.app')

@section('title', 'Sistema em Manutenção')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="bg-yellow-50 px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-yellow-800">
                            Sistema em Manutenção
                        </h3>
                        <p class="mt-1 text-sm text-yellow-700">
                            O sistema está temporariamente indisponível para manutenção.
                        </p>
                    </div>
                </div>
            </div>

            <div class="px-4 py-5 sm:p-6">
                @if($settings->project_name)
                    <div class="text-center mb-4">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $settings->project_name }}</h1>
                    </div>
                @endif

                @if($message)
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-4">
                        <div class="text-sm text-blue-800">
                            <strong>Mensagem:</strong> {{ $message }}
                        </div>
                    </div>
                @endif

                <div class="text-center text-sm text-gray-600">
                    <p>
                        Pedimos desculpas pelo inconveniente. O sistema estará disponível novamente em breve.
                    </p>
                    <p class="mt-2">
                        Para mais informações, entre em contato com a administração.
                    </p>
                </div>

                <div class="mt-6 text-center">
                    <button onclick="window.location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                        Tentar Novamente
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection