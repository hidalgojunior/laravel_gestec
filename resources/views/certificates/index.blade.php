@extends('layouts.app')

@section('title', 'Meus Certificados')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Meus Certificados</h1>
                <a href="{{ route('certificates.generate') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    Gerar Novo Certificado
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($certificates->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Atividade</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horas</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Emitido em</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($certificates as $certificate)
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($certificate->certificate_type === 'participant') bg-blue-100 text-blue-800
                                            @elseif($certificate->certificate_type === 'instructor') bg-green-100 text-green-800
                                            @elseif($certificate->certificate_type === 'organizer') bg-purple-100 text-purple-800
                                            @elseif($certificate->certificate_type === 'partner') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $certificate->getCertificateTypeLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $certificate->activity?->name ?? 'Geral' }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $certificate->total_hours }}h
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $certificate->issued_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('certificates.download', $certificate) }}"
                                           class="text-blue-600 hover:text-blue-900 mr-3" target="_blank">
                                            Download
                                        </a>
                                        <span class="text-gray-400">|</span>
                                        <a href="{{ $certificate->getValidationUrl() }}"
                                           class="text-green-600 hover:text-green-900 ml-3" target="_blank">
                                            Validar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum certificado encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Você ainda não possui certificados emitidos.
                        @if(Auth::user()->isDisqualified())
                            <br><span class="text-red-600 font-medium">Você foi desqualificado e não pode gerar certificados.</span>
                        @else
                            <br>Participe de atividades para acumular horas e gerar seu certificado.
                        @endif
                    </p>
                    @if(!Auth::user()->isDisqualified())
                        <div class="mt-6">
                            <a href="{{ route('certificates.generate') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                                Gerar Primeiro Certificado
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection