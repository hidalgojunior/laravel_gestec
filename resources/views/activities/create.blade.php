<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Atividade - GESTEC</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Criar Nova Atividade</h1>

        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nome da Atividade *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    @error('name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descrição</label>
                    <textarea name="description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Tipo *</label>
                    <select name="type" id="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Selecione o tipo</option>
                        <option value="palestra" {{ old('type') == 'palestra' ? 'selected' : '' }}>Palestra</option>
                        <option value="minicurso" {{ old('type') == 'minicurso' ? 'selected' : '' }}>Minicurso</option>
                        <option value="workshop" {{ old('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="painel" {{ old('type') == 'painel' ? 'selected' : '' }}>Painel</option>
                    </select>
                    @error('type') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="instructor_id" class="block text-gray-700 text-sm font-bold mb-2">Instrutor *</label>
                    <select name="instructor_id" id="instructor_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Selecione o instrutor</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->name }} ({{ $instructor->full_name }}) - {{ ucfirst($instructor->role) }}
                            </option>
                        @endforeach
                    </select>
                    @error('instructor_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="event_id" class="block text-gray-700 text-sm font-bold mb-2">Evento (Opcional)</label>
                    <select name="event_id" id="event_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Selecione um evento (ou deixe em branco para atividade independente)</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->title }} - {{ $event->start_date->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('event_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="start_time" class="block text-gray-700 text-sm font-bold mb-2">Data e Hora de Início *</label>
                        <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @error('start_time') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-gray-700 text-sm font-bold mb-2">Data e Hora de Término *</label>
                        <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @error('end_time') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="workload_hours" class="block text-gray-700 text-sm font-bold mb-2">Carga Horária (horas) *</label>
                        <input type="number" name="workload_hours" id="workload_hours" value="{{ old('workload_hours', 1) }}" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @error('workload_hours') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="total_spots" class="block text-gray-700 text-sm font-bold mb-2">Total de Vagas *</label>
                        <input type="number" name="total_spots" id="total_spots" value="{{ old('total_spots', 30) }}" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @error('total_spots') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="has_cost" value="1" {{ old('has_cost') ? 'checked' : '' }} class="mr-2">
                        <span class="text-gray-700 text-sm font-bold">Esta atividade tem custo?</span>
                    </label>
                </div>

                <div class="mb-4" id="pix_key_container" style="display: none;">
                    <label for="pix_key" class="block text-gray-700 text-sm font-bold mb-2">Chave PIX</label>
                    <input type="text" name="pix_key" id="pix_key" value="{{ old('pix_key') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('pix_key') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="waiting_list_enabled" value="1" {{ old('waiting_list_enabled', true) ? 'checked' : '' }} class="mr-2">
                        <span class="text-gray-700 text-sm font-bold">Habilitar fila de espera?</span>
                    </label>
                </div>

                <div class="mb-4">
                    <label for="proof_file_path" class="block text-gray-700 text-sm font-bold mb-2">Comprovante/Material (PDF, JPG, PNG)</label>
                    <input type="file" name="proof_file_path" id="proof_file_path" accept=".pdf,.jpg,.jpeg,.png" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('proof_file_path') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('activities.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Criar Atividade
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('has_cost').addEventListener('change', function() {
            document.getElementById('pix_key_container').style.display = this.checked ? 'block' : 'none';
        });

        // Mostrar container PIX se já estiver marcado
        if (document.getElementById('has_cost').checked) {
            document.getElementById('pix_key_container').style.display = 'block';
        }
    </script>
</body>
</html>