@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Configurações Globais</h2>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if (session('success'))
                <div class="mb-4 text-green-600">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf

                <!-- Project Name -->
                <div class="mb-4">
                    <label for="project_name" class="block text-sm font-medium text-gray-700">Nome do Projeto</label>
                    <input id="project_name" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="text"
                            name="project_name"
                            value="{{ old('project_name', $settings->project_name) }}"
                            required />
                    @error('project_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                    <textarea id="description" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              name="description"
                              rows="4">{{ old('description', $settings->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Theme -->
                <div class="mb-4">
                    <label for="theme" class="block text-sm font-medium text-gray-700">Tema</label>
                    <select id="theme" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            name="theme">
                        <option value="light" {{ old('theme', $settings->theme) == 'light' ? 'selected' : '' }}>Claro</option>
                        <option value="dark" {{ old('theme', $settings->theme) == 'dark' ? 'selected' : '' }}>Escuro</option>
                    </select>
                    @error('theme')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Homepage Title -->
                <div class="mb-4">
                    <label for="homepage_title" class="block text-sm font-medium text-gray-700">Título da Página Inicial</label>
                    <input id="homepage_title" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="text"
                            name="homepage_title"
                            value="{{ old('homepage_title', $settings->homepage_title) }}"
                            required />
                    @error('homepage_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Homepage Subtitle -->
                <div class="mb-4">
                    <label for="homepage_subtitle" class="block text-sm font-medium text-gray-700">Subtítulo da Página Inicial</label>
                    <input id="homepage_subtitle" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="text"
                            name="homepage_subtitle"
                            value="{{ old('homepage_subtitle', $settings->homepage_subtitle) }}" />
                    @error('homepage_subtitle')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Homepage Description -->
                <div class="mb-4">
                    <label for="homepage_description" class="block text-sm font-medium text-gray-700">Descrição da Página Inicial</label>
                    <textarea id="homepage_description" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              name="homepage_description"
                              rows="4">{{ old('homepage_description', $settings->homepage_description) }}</textarea>
                    @error('homepage_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Homepage Features -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Características (uma por linha)</label>
                    <textarea id="homepage_features" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              name="homepage_features_text"
                              rows="4"
                              placeholder="Digite cada característica em uma linha separada">{{ old('homepage_features_text', is_array($settings->homepage_features) ? implode("\n", $settings->homepage_features) : '') }}</textarea>
                    @error('homepage_features')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Homepage CTA -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="homepage_cta_text" class="block text-sm font-medium text-gray-700">Texto do Botão CTA</label>
                        <input id="homepage_cta_text" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                type="text"
                                name="homepage_cta_text"
                                value="{{ old('homepage_cta_text', $settings->homepage_cta_text) }}"
                                required />
                        @error('homepage_cta_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="homepage_cta_link" class="block text-sm font-medium text-gray-700">Link do Botão CTA</label>
                        <input id="homepage_cta_link" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                type="text"
                                name="homepage_cta_link"
                                value="{{ old('homepage_cta_link', $settings->homepage_cta_link) }}"
                                required />
                        @error('homepage_cta_link')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Certificate Settings -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações de Certificados</h3>

                    <!-- Certificate Director Signature -->
                    <div class="mb-4">
                        <label for="certificate_director_signature" class="block text-sm font-medium text-gray-700">Assinatura do Diretor</label>
                        <input id="certificate_director_signature" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               type="file"
                               name="certificate_director_signature"
                               accept="image/*" />
                        @error('certificate_director_signature')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($settings->certificate_director_signature)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings->certificate_director_signature) }}" alt="Assinatura do diretor" class="h-16 w-auto border">
                            </div>
                        @endif
                    </div>

                    <!-- Certificate Coordinator Signature -->
                    <div class="mb-4">
                        <label for="certificate_coordinator_signature" class="block text-sm font-medium text-gray-700">Assinatura do Coordenador</label>
                        <input id="certificate_coordinator_signature" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               type="file"
                               name="certificate_coordinator_signature"
                               accept="image/*" />
                        @error('certificate_coordinator_signature')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($settings->certificate_coordinator_signature)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings->certificate_coordinator_signature) }}" alt="Assinatura do coordenador" class="h-16 w-auto border">
                            </div>
                        @endif
                    </div>

                    <!-- Certificate Text Template -->
                    <div class="mb-4">
                        <label for="certificate_text_template" class="block text-sm font-medium text-gray-700">Modelo de Texto do Certificado</label>
                        <textarea id="certificate_text_template" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  name="certificate_text_template"
                                  rows="4"
                                  placeholder="Use {nome_participante}, {nome_atividade}, {carga_horaria}, {data}">{{ old('certificate_text_template', $settings->certificate_text_template) }}</textarea>
                        @error('certificate_text_template')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Validation Base URL -->
                    <div class="mb-4">
                        <label for="validation_base_url" class="block text-sm font-medium text-gray-700">URL Base para Validação</label>
                        <input id="validation_base_url" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                type="url"
                                name="validation_base_url"
                                value="{{ old('validation_base_url', $settings->validation_base_url) }}" />
                        @error('validation_base_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Salvar Configurações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection