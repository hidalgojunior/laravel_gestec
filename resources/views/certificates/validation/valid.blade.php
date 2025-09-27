@extends('layouts.app')

@section('title', 'Certificado Válido')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="bg-green-50 px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-green-800">
                            Certificado Válido
                        </h3>
                        <p class="mt-1 text-sm text-green-700">
                            Este certificado foi emitido pelo sistema GESTEC e é considerado válido.
                        </p>
                    </div>
                </div>
            </div>

            <div class="px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            Participante
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $certificate->user->full_name ?? $certificate->user->name }}
                        </dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            CPF
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $certificate->user->cpf }}
                        </dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            Tipo de Certificado
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $certificate->getCertificateTypeLabel() }}
                        </dd>
                    </div>

                    @if($certificate->activity)
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            Atividade
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $certificate->activity->name }}
                        </dd>
                    </div>
                    @endif

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            Carga Horária
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $certificate->total_hours }} horas
                        </dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            Data de Emissão
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $certificate->issued_at->format('d/m/Y') }}
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">
                            Código de Validação
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">
                            {{ $certificate->validation_code }}
                        </dd>
                    </div>
                </dl>

                <div class="mt-8 flex space-x-4">
                    <a href="{{ route('certificates.download', $certificate) }}"
                       target="_blank"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                        Download PDF
                    </a>

                    <button onclick="window.print()"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                        Imprimir
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('certificate-validation.index') }}"
               class="text-blue-600 hover:text-blue-500 text-sm">
                ← Validar outro certificado
            </a>
        </div>
    </div>
</div>
@endsection