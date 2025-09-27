<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validação de Presenças - GESTEC</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Validação de Presenças</h1>

        <div class="max-w-4xl mx-auto">
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

            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Validar Presença</h2>

                <form id="presenceForm" class="space-y-4">
                    @csrf

                    <div>
                        <label for="activity_id" class="block text-gray-700 text-sm font-bold mb-2">Selecione a Atividade *</label>
                        <select name="activity_id" id="activity_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Selecione uma atividade</option>
                            @foreach($activities as $activity)
                                <option value="{{ $activity->id }}">
                                    {{ $activity->name }} - {{ $activity->start_time->format('d/m/Y H:i') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="pin" class="block text-gray-700 text-sm font-bold mb-2">PIN do Participante (4 dígitos) *</label>
                        <input type="text" name="pin" id="pin" maxlength="4" pattern="[0-9]{4}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required placeholder="0000">
                    </div>

                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Validar Presença
                    </button>
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

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Atividades de Hoje</h3>
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
    </div>

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
</body>
</html>