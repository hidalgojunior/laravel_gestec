@extends('layouts.app')

@section('title', 'Templates de Certificado')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Templates de Certificado</h1>
                <a href="{{ route('certificate-templates.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    Novo Template
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($templates->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($templates as $template)
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition duration-200">
                            <div class="aspect-w-16 aspect-h-9 bg-gray-100 relative">
                                @if($template->background_image_path)
                                    <img src="{{ asset('storage/' . $template->background_image_path) }}"
                                         alt="{{ $template->name }}"
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                                @if($template->is_global)
                                    <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded text-xs font-medium">
                                        Global
                                    </div>
                                @else
                                    <div class="absolute top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded text-xs font-medium">
                                        Específico
                                    </div>
                                @endif
                            </div>

                            <div class="p-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $template->name }}</h3>

                                @if($template->activity)
                                    <p class="text-sm text-gray-600 mb-2">
                                        <strong>Atividade:</strong> {{ $template->activity->name }}
                                    </p>
                                @else
                                    <p class="text-sm text-green-600 mb-2">
                                        <strong>Template Global</strong>
                                    </p>
                                @endif

                                <p class="text-sm text-gray-500 mb-4">
                                    Criado em {{ $template->created_at->format('d/m/Y') }}
                                </p>

                                <div class="flex space-x-2">
                                    <a href="{{ route('certificate-templates.edit', $template) }}"
                                       class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded text-sm text-center transition duration-200">
                                        Editar
                                    </a>
                                    <form action="{{ route('certificate-templates.destroy', $template) }}"
                                          method="POST"
                                          class="flex-1"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este template?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm transition duration-200">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum template encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Crie seu primeiro template de certificado para começar.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('certificate-templates.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                            Criar Primeiro Template
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection