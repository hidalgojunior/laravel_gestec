@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Detalhes do Usuário: {{ $user->full_name }}</h2>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <strong class="block text-gray-700 text-sm font-bold mb-2">Nome Completo:</strong>
                            <p class="text-gray-900">{{ $user->full_name }}</p>
                        </div>

                        <div>
                            <strong class="block text-gray-700 text-sm font-bold mb-2">Nome de Exibição:</strong>
                            <p class="text-gray-900">{{ $user->name }}</p>
                        </div>

                        <div>
                            <strong class="block text-gray-700 text-sm font-bold mb-2">CPF:</strong>
                            <p class="text-gray-900">{{ $user->cpf }}</p>
                        </div>

                        <div>
                            <strong class="block text-gray-700 text-sm font-bold mb-2">E-mail:</strong>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>

                        <div>
                            <strong class="block text-gray-700 text-sm font-bold mb-2">Papel:</strong>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($user->role === 'administrador') bg-red-100 text-red-800
                                @elseif($user->role === 'organizador') bg-blue-100 text-blue-800
                                @elseif($user->role === 'ministrador') bg-green-100 text-green-800
                                @elseif($user->role === 'colaborador') bg-yellow-100 text-yellow-800
                                @elseif($user->role === 'participante') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>

                        @if($user->whatsapp)
                        <div>
                            <strong class="block text-gray-700 text-sm font-bold mb-2">WhatsApp:</strong>
                            <p class="text-gray-900">{{ $user->whatsapp }}</p>
                        </div>
                        @endif

                        @if($user->person_type)
                        <div>
                            <strong class="block text-gray-700 text-sm font-bold mb-2">Tipo de Pessoa:</strong>
                            <p class="text-gray-900">{{ $user->person_type === 'pf' ? 'Pessoa Física' : 'Pessoa Jurídica' }}</p>
                        </div>
                        @endif

                        @if($user->signature_image)
                        <div class="md:col-span-2">
                            <strong class="block text-gray-700 text-sm font-bold mb-2">Assinatura Digital:</strong>
                            <img src="{{ asset('storage/' . $user->signature_image) }}" alt="Assinatura" class="max-w-xs border rounded">
                        </div>
                        @endif

                        @if($user->additional_contacts)
                        <div class="md:col-span-2">
                            <strong class="block text-gray-700 text-sm font-bold mb-2">Contatos Adicionais:</strong>
                            <ul class="list-disc list-inside text-gray-900">
                                @foreach($user->additional_contacts as $contact)
                                    <li>{{ $contact }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-3">
                            Editar
                        </a>
                        <a href="{{ route('admin.users.index', ['role' => $user->role]) }}"
                           class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection