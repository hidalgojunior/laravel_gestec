<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atividades - GESTEC</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Gerenciamento de Atividades</h1>

        <div class="mb-4">
            <a href="{{ route('activities.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Criar Nova Atividade
            </a>
            <a href="{{ route('enrollments.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2">
                Ver Inscrições
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

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ministrador</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data/Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vagas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($activities as $activity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($activity->type === 'palestra') bg-blue-100 text-blue-800
                                    @elseif($activity->type === 'minicurso') bg-green-100 text-green-800
                                    @elseif($activity->type === 'workshop') bg-yellow-100 text-yellow-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    {{ ucfirst($activity->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->instructor->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $activity->start_time->format('d/m/Y H:i') }}<br>
                                <small class="text-gray-500">até {{ $activity->end_time->format('H:i') }}</small>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $activity->enrollments->where('status', 'confirmed')->count() }}/{{ $activity->total_spots }}
                                @if($activity->waiting_list_enabled)
                                    <br><small class="text-orange-600">{{ $activity->enrollments->where('status', 'waiting_list')->count() }} fila</small>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('activities.show', $activity) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Ver</a>
                                <a href="{{ route('activities.edit', $activity) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Editar</a>
                                <a href="{{ route('presences.report', $activity) }}" class="text-green-600 hover:text-green-900 mr-2">Presenças</a>
                                <form action="{{ route('activities.destroy', $activity) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir esta atividade?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>