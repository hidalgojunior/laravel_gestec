<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Configurações Globais') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Project Name -->
                        <div>
                            <x-input-label for="project_name" :value="__('Nome do Projeto')" />
                            <x-text-input id="project_name" class="block mt-1 w-full"
                                            type="text"
                                            name="project_name"
                                            :value="old('project_name', $settings->project_name)"
                                            required />
                            <x-input-error :messages="$errors->get('project_name')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Descrição')" />
                            <textarea id="description" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      name="description"
                                      rows="4">{{ old('description', $settings->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Logo -->
                        <div class="mt-4">
                            <x-input-label for="logo" :value="__('Logo')" />
                            <input id="logo" class="block mt-1 w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100"
                                   type="file"
                                   name="logo"
                                   accept="image/*" />
                            <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                            @if($settings->logo_path)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo atual" class="h-16 w-auto">
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Salvar Configurações') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>