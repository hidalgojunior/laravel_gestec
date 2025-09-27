<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">{{ $event->title }}</h1>

        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <strong class="block text-gray-700 text-sm font-bold mb-2">Descrição:</strong>
                <p class="text-gray-700">{{ $event->description }}</p>
            </div>

            <div class="mb-4">
                <strong class="block text-gray-700 text-sm font-bold mb-2">Data e Hora de Início:</strong>
                <p class="text-gray-700">{{ $event->start_date->format('d/m/Y H:i') }}</p>
            </div>

            <div class="mb-4">
                <strong class="block text-gray-700 text-sm font-bold mb-2">Data e Hora de Fim:</strong>
                <p class="text-gray-700">{{ $event->end_date ? $event->end_date->format('d/m/Y H:i') : 'Não definida' }}</p>
            </div>

            <div class="mb-4">
                <strong class="block text-gray-700 text-sm font-bold mb-2">Local:</strong>
                <p class="text-gray-700">{{ $event->location }}</p>
            </div>

            <div class="mb-4">
                <strong class="block text-gray-700 text-sm font-bold mb-2">Capacidade:</strong>
                <p class="text-gray-700">{{ $event->capacity }}</p>
            </div>

            <div class="mb-4">
                <strong class="block text-gray-700 text-sm font-bold mb-2">Preço:</strong>
                <p class="text-gray-700">R$ {{ number_format($event->price, 2, ',', '.') }}</p>
            </div>

            <!-- Atividades do Evento -->
            <div class="mb-6">
                <strong class="block text-gray-700 text-sm font-bold mb-2">Atividades do Evento:</strong>
                @if($event->activities->count() > 0)
                    <div class="space-y-2">
                        @foreach($event->activities as $activity)
                            <div class="bg-gray-50 p-3 rounded border">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold">{{ $activity->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $activity->description }}</p>
                                        <p class="text-sm text-gray-500">
                                            <strong>Tipo:</strong> {{ ucfirst($activity->type) }} |
                                            <strong>Instrutor:</strong> {{ $activity->instructor->name }} |
                                            <strong>Data/Hora:</strong> {{ $activity->start_time->format('d/m/Y H:i') }} - {{ $activity->end_time->format('H:i') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm text-gray-500">{{ $activity->enrollments()->where('status', 'confirmed')->count() }}/{{ $activity->total_spots }} vagas</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic">Nenhuma atividade cadastrada para este evento.</p>
                @endif
            </div>

            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <a href="{{ route('events.edit', $event) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Editar Evento
                    </a>
                    @auth
                        <form method="POST" action="{{ route('event-enrollments.enroll', $event) }}" class="inline">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Inscrever-se no Evento
                            </button>
                        </form>
                    @endauth
                </div>
                <a href="{{ route('events.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Voltar aos Eventos
                </a>
            </div>
        </div>
    </div>
</body>
</html>