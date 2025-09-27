@extends('layouts.app')

@section('title', 'Certificado Inválido')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="bg-red-50 px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-red-800">
                            Certificado Inválido
                        </h3>
                        <p class="mt-1 text-sm text-red-700">
                            {{ $error }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="px-4 py-5 sm:p-6">
                <div class="text-sm text-gray-600">
                    <p class="mb-4">
                        <strong>Código informado:</strong> {{ $code }}
                    </p>

                    <p>
                        Se você acredita que este certificado deveria ser válido, entre em contato com a organização do evento.
                    </p>
                </div>

                <div class="mt-6">
                    <a href="{{ route('certificate-validation.index') }}"
                       class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        Tentar outro código
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection