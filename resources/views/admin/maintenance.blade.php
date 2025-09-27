@extends('layouts.app')

@section('title', 'Manutenção do Sistema')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Manutenção do Sistema</h1>
                <p class="text-gray-600 mt-2">Gerencie o modo de manutenção e backups do sistema</p>
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

            <!-- Modo de Manutenção -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Modo de Manutenção</h2>

                <form action="{{ route('admin.maintenance.toggle') }}" method="POST" class="bg-gray-50 p-6 rounded-lg">
                    @csrf

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="maintenance_mode"
                                   value="1"
                                   {{ $settings->isMaintenanceMode() ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Ativar modo de manutenção</span>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label for="maintenance_message" class="block text-sm font-medium text-gray-700 mb-2">
                            Mensagem (opcional)
                        </label>
                        <textarea name="maintenance_message"
                                  id="maintenance_message"
                                  rows="3"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Digite uma mensagem explicando o motivo da manutenção...">{{ $settings->maintenance_message }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                            {{ $settings->isMaintenanceMode() ? 'Desativar' : 'Ativar' }} Manutenção
                        </button>
                    </div>
                </form>

                @if($settings->isMaintenanceMode())
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Modo de Manutenção Ativo
                                </h3>
                                <p class="mt-1 text-sm text-yellow-700">
                                    O sistema está em manutenção. Apenas administradores podem acessar.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Backup e Restauração -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Backup e Restauração</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Backup -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Criar Backup</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Faça download de um backup completo do banco de dados MySQL.
                        </p>
                        <a href="{{ route('admin.backup') }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Backup
                        </a>
                    </div>

                    <!-- Restauração -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Restaurar Backup</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Faça upload de um arquivo SQL para restaurar o banco de dados.
                        </p>
                        <form action="{{ route('admin.restore') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input type="file"
                                       name="backup_file"
                                       accept=".sql"
                                       required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <button type="submit"
                                    onclick="return confirm('Tem certeza que deseja restaurar o backup? Isso irá sobrescrever todos os dados atuais.')"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Restaurar Backup
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Importante
                            </h3>
                            <ul class="mt-1 text-sm text-blue-700 list-disc list-inside">
                                <li>Faça backups regulares para evitar perda de dados</li>
                                <li>Teste o backup em um ambiente de desenvolvimento antes de usar em produção</li>
                                <li>A restauração sobrescreverá todos os dados atuais</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection