@extends('layouts.app')

@section('title', 'Criar Template de Certificado')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Criar Template de Certificado</h1>
                <p class="text-gray-600 mt-2">Configure um novo template para geração de certificados</p>
            </div>

            <form action="{{ route('certificate-templates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <!-- Nome -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nome do Template <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Tipo de Template <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="radio"
                                       name="is_global"
                                       id="global"
                                       value="1"
                                       {{ old('is_global', '1') == '1' ? 'checked' : '' }}
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <label for="global" class="ml-2 block text-sm text-gray-900">
                                    <strong>Global</strong> - Usado para todas as atividades que não têm template específico
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio"
                                       name="is_global"
                                       id="specific"
                                       value="0"
                                       {{ old('is_global') == '0' ? 'checked' : '' }}
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <label for="specific" class="ml-2 block text-sm text-gray-900">
                                    <strong>Específico</strong> - Usado apenas para uma atividade selecionada
                                </label>
                            </div>
                        </div>
                        @error('is_global')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Atividade (só mostra se não for global) -->
                    <div id="activity-field" class="{{ old('is_global', '1') == '1' ? 'hidden' : '' }}">
                        <label for="activity_id" class="block text-sm font-medium text-gray-700">
                            Atividade
                        </label>
                        <select name="activity_id"
                                id="activity_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Selecione uma atividade...</option>
                            @foreach($activities as $activity)
                                <option value="{{ $activity->id }}" {{ old('activity_id') == $activity->id ? 'selected' : '' }}>
                                    {{ $activity->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('activity_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Imagem de fundo -->
                    <div>
                        <label for="background_image" class="block text-sm font-medium text-gray-700">
                            Imagem de Fundo <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="background_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload de imagem</span>
                                        <input id="background_image" name="background_image" type="file" accept="image/*" class="sr-only">
                                    </label>
                                    <p class="pl-1">ou arraste e solte</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, GIF até 2MB (recomendado: 1920x1080 para paisagem)
                                </p>
                            </div>
                        </div>
                        @error('background_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview da imagem -->
                    <div id="image-preview" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                        <img id="preview-img" src="" alt="Preview" class="max-w-full h-auto rounded-md border">
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('certificate-templates.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-200">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        Criar Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle activity field based on template type
document.querySelectorAll('input[name="is_global"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const activityField = document.getElementById('activity-field');
        if (this.value === '1') {
            activityField.classList.add('hidden');
        } else {
            activityField.classList.remove('hidden');
        }
    });
});

// Image preview
document.getElementById('background_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});
</script>
@endsection