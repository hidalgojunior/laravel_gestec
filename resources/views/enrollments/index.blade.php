@extends('layouts.app')

@section('title', 'Inscrições em Atividades')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Inscrições em Atividades</h2>
        <p class="mt-1 text-sm text-gray-600">Inscreva-se nas atividades disponíveis</p>
    </div>

    @if(Auth::user()->is_disqualified)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong>Atenção!</strong> Você foi desqualificado do evento devido à ausência em atividades obrigatórias.
            Motivo: {{ Auth::user()->disqualification_reason }}
        </div>
    @else
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
            <strong>Regra Crítica:</strong> Se você se inscrever em uma atividade e não registrar presença (0%),
            será automaticamente desqualificado do evento, perdendo o direito a TODOS os certificados do GESTEC.
        </div>
    @endif

    <div class="mb-6">
        <a href="{{ route('enrollments.my-enrollments') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
            Minhas Inscrições
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($activities as $activity)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $activity->name }}</h3>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($activity->type === 'palestra') bg-blue-100 text-blue-800
                        @elseif($activity->type === 'minicurso') bg-green-100 text-green-800
                        @elseif($activity->type === 'workshop') bg-yellow-100 text-yellow-800
                        @else bg-purple-100 text-purple-800 @endif">
                        {{ ucfirst($activity->type) }}
                    </span>
                </div>

                <div class="mb-4 text-sm text-gray-600">
                    <p><strong>Ministrador:</strong> {{ $activity->instructor->name }}</p>
                    <p><strong>Data/Hora:</strong> {{ $activity->start_time->format('d/m/Y H:i') }}</p>
                    <p><strong>Duração:</strong> {{ $activity->workload_hours }} horas</p>
                    <p><strong>Vagas:</strong>
                        {{ $activity->enrollments->where('status', 'confirmed')->count() }}/{{ $activity->total_spots }}
                        @if($activity->waiting_list_enabled)
                            ({{ $activity->enrollments->where('status', 'waiting_list')->count() }} fila de espera)
                        @endif
                    </p>
                    @if($activity->has_cost)
                        <p><strong>Custo:</strong> Sim (PIX: {{ $activity->pix_key }})</p>
                    @endif
                </div>

                @if($activity->description)
                    <div class="mb-4">
                        <p class="text-sm text-gray-700">{{ Str::limit($activity->description, 100) }}</p>
                    </div>
                @endif

                <div class="flex justify-between items-center">
                    @php
                        $userEnrollment = $activity->enrollments->where('user_id', Auth::id())->first();
                    @endphp

                    @if($userEnrollment)
                        @if($userEnrollment->status === 'confirmed')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                ✓ Inscrito
                            </span>
                            <form action="{{ route('enrollments.cancel', $userEnrollment) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded"
                                        onclick="return confirm('Tem certeza que deseja cancelar sua inscrição?')">
                                    Cancelar
                                </button>
                            </form>
                        @elseif($userEnrollment->status === 'waiting_list')
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                Fila de Espera
                            </span>
                            <form action="{{ route('enrollments.cancel', $userEnrollment) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded"
                                        onclick="return confirm('Tem certeza que deseja sair da fila de espera?')">
                                    Sair da Fila
                                </button>
                            </form>
                        @endif
                    @else
                        @if(Auth::user()->is_disqualified)
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                                Indisponível
                            </span>
                        @elseif($activity->hasAvailableSpots() || $activity->canUseWaitingList())
                            <form action="{{ route('enrollments.enroll', $activity) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    {{ $activity->hasAvailableSpots() ? 'Inscrever-se' : 'Entrar na Fila' }}
                                </button>
                            </form>
                        @else
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                Esgotado
                            </span>
                        @endif
                    @endif
                </div>

                @if($userEnrollment && $userEnrollment->status === 'confirmed' && $activity->has_cost && !$userEnrollment->payment_confirmed)
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                        <p class="text-sm text-yellow-800 mb-2">Pagamento pendente. Envie o comprovante:</p>
                        <form action="{{ route('enrollments.upload-proof', $userEnrollment) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="proof_file" accept=".pdf,.jpg,.jpeg,.png" required class="text-sm mb-2">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white text-sm font-bold py-1 px-3 rounded">
                                Enviar Comprovante
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection