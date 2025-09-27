@extends('layouts.app')

@section('title', 'Minhas Inscrições')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Minhas Inscrições</h2>
        <p class="mt-1 text-sm text-gray-600">Gerencie suas inscrições em atividades</p>
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

    @if($enrollments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrollments as $enrollment)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $enrollment->activity->name }}</h3>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($enrollment->activity->type === 'palestra') bg-blue-100 text-blue-800
                            @elseif($enrollment->activity->type === 'minicurso') bg-green-100 text-green-800
                            @elseif($enrollment->activity->type === 'workshop') bg-yellow-100 text-yellow-800
                            @else bg-purple-100 text-purple-800 @endif">
                            {{ ucfirst($enrollment->activity->type) }}
                        </span>
                    </div>

                    <div class="mb-4 text-sm text-gray-600">
                        <p><strong>Ministrador:</strong> {{ $enrollment->activity->instructor->name }}</p>
                        <p><strong>Data/Hora:</strong> {{ $enrollment->activity->start_time->format('d/m/Y H:i') }}</p>
                        <p><strong>Duração:</strong> {{ $enrollment->activity->workload_hours }} horas</p>
                        <p><strong>Local:</strong> {{ $enrollment->activity->location ?? 'A definir' }}</p>
                    </div>

                    <div class="mb-4">
                        @if($enrollment->status === 'confirmed')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                ✓ Inscrito
                            </span>
                        @elseif($enrollment->status === 'waiting_list')
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                Lista de Espera
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        @endif
                    </div>

                    @if($enrollment->status === 'confirmed')
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Status de Presença:</h4>
                            @if($enrollment->presence)
                                @if($enrollment->presence->validated)
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                        ✓ Presença Validada
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Validada em: {{ $enrollment->presence->validated_at->format('d/m/Y H:i') }}
                                    </p>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                        Presença Registrada
                                    </span>
                                @endif
                            @else
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                    Presença Não Registrada
                                </span>
                            @endif
                        </div>
                    @endif

                    @if($enrollment->activity->has_cost && $enrollment->status === 'confirmed')
                        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                            <p class="text-sm text-yellow-800">
                                <strong>Pagamento:</strong>
                                @if($enrollment->payment_confirmed)
                                    <span class="text-green-600">Confirmado</span>
                                @else
                                    <span class="text-orange-600">Pendente</span>
                                    @if(!$enrollment->proof_file_path)
                                        <br><small>Envie o comprovante de pagamento.</small>
                                    @endif
                                @endif
                            </p>
                        </div>
                    @endif

                    <div class="flex justify-between items-center">
                        @if($enrollment->status === 'confirmed')
                            <form action="{{ route('enrollments.cancel', $enrollment) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded"
                                        onclick="return confirm('Tem certeza que deseja cancelar sua inscrição?')">
                                    Cancelar Inscrição
                                </button>
                            </form>
                        @elseif($enrollment->status === 'waiting_list')
                            <form action="{{ route('enrollments.cancel', $enrollment) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white text-sm font-bold py-1 px-3 rounded"
                                        onclick="return confirm('Tem certeza que deseja sair da lista de espera?')">
                                    Sair da Lista
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma inscrição encontrada</h3>
            <p class="mt-1 text-sm text-gray-500">Você ainda não se inscreveu em nenhuma atividade.</p>
            <div class="mt-6">
                <a href="{{ route('enrollments.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                    Ver Atividades Disponíveis
                </a>
            </div>
        </div>
    @endif
</div>
@endsection