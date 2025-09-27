@extends('layouts.app')

@section('title', 'Validação de Presenças')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Validação de Presenças</h2>
        <p class="mt-1 text-sm text-gray-600">Valide a presença dos participantes nas atividades</p>
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

    <div class="bg-white shadow overflow-hidden sm:rounded-md mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Validar Presença</h3>

            <form id="presenceForm" class="space-y-4">
                @csrf

                <div>
                    <label for="activity_id" class="block text-sm font-medium text-gray-700">Selecione a Atividade *</label>
                    <select name="activity_id" id="activity_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="">Selecione uma atividade</option>
                        @foreach($activities as $activity)
                            <option value="{{ $activity->id }}">
                                {{ $activity->name }} - {{ $activity->start_time->format('d/m/Y H:i') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="pin" class="block text-sm font-medium text-gray-700">PIN do Participante (4 dígitos) *</label>
                    <input type="text" name="pin" id="pin" maxlength="4" pattern="[0-9]{4}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required placeholder="0000">
                </div>

                <div>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Validar Presença
                    </button>
                </div>
            </form>

            <div id="result" class="mt-4 hidden">
                <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded hidden">
                    <span id="successText"></span>
                </div>
                <div id="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded hidden">
                    <span id="errorText"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Atividades de Hoje</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Atividade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inscritos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Presentes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($activities as $activity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $activity->name }}</div>
                                <div class="text-sm text-gray-500">{{ $activity->instructor->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $activity->start_time->format('d/m/Y') }}<br>
                                {{ $activity->start_time->format('H:i') }} - {{ $activity->end_time->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $activity->enrollments->where('status', 'confirmed')->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $activity->enrollments->where('status', 'confirmed')->filter(function($enrollment) {
                                    return $enrollment->hasPresenceValidated();
                                })->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('presences.report', $activity) }}" class="text-indigo-600 hover:text-indigo-900">
                                    Ver Relatório
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('presenceForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const resultDiv = document.getElementById('result');
        const successDiv = document.getElementById('successMessage');
        const errorDiv = document.getElementById('errorMessage');
        const successText = document.getElementById('successText');
        const errorText = document.getElementById('errorText');

        fetch('{{ route("presences.validate") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            resultDiv.classList.remove('hidden');
            successDiv.classList.add('hidden');
            errorDiv.classList.add('hidden');

            if (data.success) {
                successDiv.classList.remove('hidden');
                successText.textContent = data.message + ' - ' + data.participant;
                document.getElementById('pin').value = '';
            } else {
                errorDiv.classList.remove('hidden');
                errorText.textContent = data.error;
            }
        })
        .catch(error => {
            resultDiv.classList.remove('hidden');
            successDiv.classList.add('hidden');
            errorDiv.classList.remove('hidden');
            errorText.textContent = 'Erro ao processar a solicitação.';
        });
    });

    // Auto-focus no campo PIN
    document.getElementById('pin').focus();
</script>
@endsection