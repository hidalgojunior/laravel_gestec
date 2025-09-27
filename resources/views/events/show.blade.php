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
                <strong class="block text-gray-700 text-sm font-bold mb-2">Data do Evento:</strong>
                <p class="text-gray-700">{{ $event->event_date->format('d/m/Y H:i') }}</p>
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

            <div class="flex items-center justify-between">
                <a href="{{ route('events.edit', $event) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Editar Evento
                </a>
                <a href="{{ route('events.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Voltar aos Eventos
                </a>
            </div>
        </div>
    </div>
</body>
</html>