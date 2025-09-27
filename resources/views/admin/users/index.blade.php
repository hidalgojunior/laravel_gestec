@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Gerenciamento de Usuários</h2>
    </div>

    <!-- Filtros por papel -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.users.index', ['role' => 'todos']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ $role === 'todos' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Todos
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'administrador']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ $role === 'administrador' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Administradores
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'organizador']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ $role === 'organizador' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Organizadores
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'ministrador']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ $role === 'ministrador' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Ministradores
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'palestrante']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ $role === 'palestrante' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Palestrantes
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'colaborador']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ $role === 'colaborador' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Colaboradores
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'participante']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ $role === 'participante' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Participantes
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'parceiro']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ $role === 'parceiro' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Parceiros
            </a>
        </div>
    </div>

    <!-- Botão de criar usuário -->
    <div class="mb-6">
        <a href="{{ route('admin.users.create', ['role' => $role === 'todos' ? 'participante' : $role]) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Cadastrar Novo {{ ucfirst($role === 'todos' ? 'Usuário' : $role) }}
        </a>
    </div>

    <!-- Tabela de usuários -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPF</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-mail</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Papel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $user->full_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->cpf }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                    @if(!$user->is_protected)
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Excluir</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Nenhum usuário encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection