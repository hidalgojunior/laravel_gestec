<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar ') . ucfirst($user->role) . ': ' . $user->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nome Completo -->
                        <div class="mb-4">
                            <x-input-label for="full_name" :value="__('Nome Completo')" />
                            <x-text-input id="full_name" class="block mt-1 w-full" type="text" name="full_name"
                                        :value="old('full_name', $user->full_name)" required autofocus />
                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                        </div>

                        <!-- CPF -->
                        <div class="mb-4">
                            <x-input-label for="cpf" :value="__('CPF')" />
                            <x-text-input id="cpf" class="block mt-1 w-full" type="text" name="cpf"
                                        :value="old('cpf', $user->cpf)" required />
                            <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
                        </div>

                        <!-- Nome (para exibição) -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nome de Exibição')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                        :value="old('name', $user->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('E-mail')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- WhatsApp -->
                        <div class="mb-4">
                            <x-input-label for="whatsapp" :value="__('WhatsApp')" />
                            <x-text-input id="whatsapp" class="block mt-1 w-full" type="text" name="whatsapp"
                                        :value="old('whatsapp', $user->whatsapp)" />
                            <x-input-error :messages="$errors->get('whatsapp')" class="mt-2" />
                        </div>

                        <!-- Tipo de Pessoa (apenas para organizador) -->
                        @if($user->role === 'organizador')
                        <div class="mb-4">
                            <x-input-label for="person_type" :value="__('Tipo de Pessoa')" />
                            <select id="person_type" name="person_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="pf" {{ old('person_type', $user->person_type) === 'pf' ? 'selected' : '' }}>Pessoa Física (PF)</option>
                                <option value="pj" {{ old('person_type', $user->person_type) === 'pj' ? 'selected' : '' }}>Pessoa Jurídica (PJ)</option>
                            </select>
                            <x-input-error :messages="$errors->get('person_type')" class="mt-2" />
                        </div>

                        <!-- Assinatura Digital Atual -->
                        @if($user->signature_image)
                        <div class="mb-4">
                            <strong class="block text-gray-700 text-sm font-bold mb-2">Assinatura Atual:</strong>
                            <img src="{{ asset('storage/' . $user->signature_image) }}" alt="Assinatura atual" class="max-w-xs border rounded mb-2">
                        </div>
                        @endif

                        <!-- Nova Assinatura Digital -->
                        <div class="mb-4">
                            <x-input-label for="signature_image" :value="__('Nova Assinatura Digital (Opcional)')" />
                            <input id="signature_image" type="file" name="signature_image"
                                   class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                   accept="image/*" />
                            <p class="mt-1 text-sm text-gray-500">Deixe em branco para manter a assinatura atual. Formatos aceitos: JPEG, PNG, JPG, GIF. Tamanho máximo: 2MB</p>
                            <x-input-error :messages="$errors->get('signature_image')" class="mt-2" />
                        </div>
                        @endif

                        <!-- Senha (opcional) -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Nova Senha (Deixe em branco para manter a atual)')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password"
                                        name="password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirmar Senha -->
                        <div class="mb-4">
                            <x-input-label for="password_confirmation" :value="__('Confirmar Nova Senha')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                        name="password_confirmation" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                Cancelar
                            </a>

                            <x-primary-button>
                                {{ __('Atualizar ') . ucfirst($user->role) }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Máscara para CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                e.target.value = value;
            }
        });

        // Máscara para WhatsApp
        document.getElementById('whatsapp')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                e.target.value = value;
            }
        });
    </script>
</x-app-layout>