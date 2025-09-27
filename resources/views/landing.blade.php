<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="GESTEC - Evento de Tecnologia e Inovação - GRATUITO">
    <title>GESTEC - Evento de Tecnologia</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-black">
    <!-- Header -->
    <header class="bg-blue-900 text-white py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="text-2xl font-bold">{{ $settings->project_name ?? 'GESTEC' }}</div>
            <nav class="hidden md:flex space-x-6">
                <a href="#sobre" class="hover:text-orange-400 transition">Sobre</a>
                <a href="#instrucoes" class="hover:text-orange-400 transition">Instruções</a>
                <a href="#programacao" class="hover:text-orange-400 transition">Programação</a>
                <a href="#organizadores" class="hover:text-orange-400 transition">Organizadores</a>
                <a href="#contato" class="hover:text-orange-400 transition">Contato</a>
            </nav>
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded transition">Área Restrita</a>
                <button class="md:hidden" onclick="toggleMenu()">☰</button>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden bg-blue-800 p-4">
            <a href="#sobre" class="block py-2 hover:text-orange-400">Sobre</a>
            <a href="#instrucoes" class="block py-2 hover:text-orange-400">Instruções</a>
            <a href="#programacao" class="block py-2 hover:text-orange-400">Programação</a>
            <a href="#organizadores" class="block py-2 hover:text-orange-400">Organizadores</a>
            <a href="#contato" class="block py-2 hover:text-orange-400">Contato</a>
            <a href="{{ route('login') }}" class="block py-2 bg-orange-500 text-white text-center rounded mt-2">Área Restrita</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-blue-900 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ $settings->homepage_title ?? 'Bem-vindo ao GESTEC' }}</h1>
            <p class="text-xl md:text-2xl mb-8">{{ $settings->homepage_subtitle ?? 'Sistema de Gerenciamento de Eventos Técnicos' }}</p>
            <p class="text-lg mb-6 max-w-2xl mx-auto">{{ $settings->homepage_description ?? 'Gerencie seus eventos, atividades e participantes de forma eficiente.' }}</p>

            <!-- Destaque GRATUITO -->
            <div class="bg-orange-500 text-white px-8 py-4 rounded-lg mb-8 inline-block">
                <h2 class="text-2xl md:text-3xl font-bold">EVENTO 100% GRATUITO</h2>
                <p class="text-lg">Não é necessário pagamento de valores para participação</p>
            </div>

            <!-- Período de Inscrição -->
            <div class="bg-white text-blue-900 px-6 py-4 rounded-lg mb-8">
                <h3 class="text-xl font-bold mb-2">Período de Inscrições</h3>
                <p class="text-lg">
                    <strong>Abertura:</strong> {{ \Carbon\Carbon::parse($registrationPeriod['start'])->format('d/m/Y') }}<br>
                    <strong>Encerramento:</strong> {{ \Carbon\Carbon::parse($registrationPeriod['end'])->format('d/m/Y') }}
                </p>
            </div>

            <a href="{{ $settings->homepage_cta_link ?? '/dashboard' }}" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-lg text-lg font-semibold transition">{{ $settings->homepage_cta_text ?? 'Começar Agora' }}</a>
        </div>
    </section>

    <!-- Sobre Section -->
    <section id="sobre" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-blue-900">Sobre o GESTEC</h2>
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-lg mb-6">O GESTEC é o maior evento de tecnologia da região, reunindo profissionais, estudantes e entusiastas para compartilhar conhecimento e experiências inovadoras.</p>
                    <p class="text-lg mb-6">Com palestras de alto nível, workshops práticos e oportunidades de networking, o evento oferece uma experiência única de aprendizado e conexão.</p>
                    <p class="text-lg mb-6"><strong>Organizador:</strong> {{ $etecInfo['name'] }}</p>
                </div>
                <div class="bg-blue-900 text-white p-8 rounded-lg">
                    <h3 class="text-2xl font-bold mb-4">Por que participar?</h3>
                    <ul class="space-y-2">
                        @if($settings->homepage_features && is_array($settings->homepage_features))
                            @foreach($settings->homepage_features as $feature)
                                <li>• {{ $feature }}</li>
                            @endforeach
                        @else
                            <li>• Palestras com experts renomados</li>
                            <li>• Workshops hands-on</li>
                            <li>• Networking com profissionais</li>
                            <li>• Certificado de participação</li>
                            <li>• Coffee breaks e almoços</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Instruções e Regulamento -->
    <section id="instrucoes" class="py-20 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-blue-900">Instruções e Regulamento</h2>

            <div class="max-w-4xl mx-auto">
                <!-- Datas Limite -->
                <div class="bg-orange-100 border-l-4 border-orange-500 p-6 mb-8">
                    <h3 class="text-xl font-bold text-orange-800 mb-2">⚠️ ATENÇÃO - Datas Limite</h3>
                    <p class="text-lg text-orange-700">
                        <strong>Inscrições abertas até:</strong> {{ \Carbon\Carbon::parse($registrationPeriod['end'])->format('d/m/Y') }}<br>
                        <strong>Não perca o prazo!</strong> Após esta data não será possível se inscrever.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-xl font-bold mb-4 text-blue-900">Como Participar</h3>
                        <ol class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <span class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3 mt-0.5">1</span>
                                <span>Faça seu cadastro no sistema</span>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3 mt-0.5">2</span>
                                <span>Selecione as atividades de seu interesse</span>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3 mt-0.5">3</span>
                                <span>Confirme sua presença nos eventos</span>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3 mt-0.5">4</span>
                                <span>Receba seu certificado de participação</span>
                            </li>
                        </ol>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-xl font-bold mb-4 text-blue-900">Requisitos Obrigatórios</h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                <span><strong>Nome completo</strong> obrigatório para certificação</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-500 mr-2">✓</span>
                                <span><strong>CPF válido</strong> para identificação</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-red-500 mr-2">⚠️</span>
                                <span><strong>Regra crítica:</strong> Falta em atividade = perda de todos os certificados</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="text-center mt-8">
                    <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-lg text-lg font-semibold transition">Fazer Inscrição</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Cronograma de Atividades -->
    <section id="programacao" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-blue-900">Cronograma de Atividades</h2>

            @if($activities->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($activities as $activity)
                    <div class="bg-gray-50 p-6 rounded-lg shadow-lg border-l-4 border-orange-500">
                        <h3 class="text-xl font-bold mb-2 text-blue-900">{{ $activity->name }}</h3>
                        <p class="text-gray-600 mb-2">
                            <strong>Ministrador:</strong> {{ $activity->instructor->name ?? 'A definir' }}
                        </p>
                        <p class="text-gray-600 mb-2">
                            <strong>Horário:</strong> {{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($activity->end_time)->format('H:i') }}
                        </p>
                        @if($activity->location)
                        <p class="text-gray-600 mb-4">
                            <strong>Local:</strong> {{ $activity->location }}
                        </p>
                        @endif
                        <a href="{{ route('login') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded text-sm transition">Inscrever-se</a>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500">
                    <p class="text-lg">Atividades serão anunciadas em breve.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Organizadores e Contato -->
    <section id="organizadores" class="py-20 bg-blue-900 text-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Organizadores e Contato</h2>

            <div class="grid md:grid-cols-2 gap-12">
                <!-- Organizadores -->
                <div>
                    <h3 class="text-2xl font-bold mb-6 text-orange-400">Organizadores</h3>
                    @if($organizers->count() > 0)
                        <div class="space-y-4">
                            @foreach($organizers as $organizer)
                            <div class="bg-white text-black p-4 rounded-lg">
                                <h4 class="font-bold">{{ $organizer->name }}</h4>
                                <p class="text-gray-600">{{ $organizer->email }}</p>
                                @if($organizer->person_type === 'pj')
                                    <p class="text-sm text-gray-500">Pessoa Jurídica</p>
                                @else
                                    <p class="text-sm text-gray-500">Pessoa Física</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-300">Organizadores serão anunciados em breve.</p>
                    @endif
                </div>

                <!-- Contato da Etec -->
                <div>
                    <h3 class="text-2xl font-bold mb-6 text-orange-400">Etec Antonio Devisate</h3>
                    <div class="bg-white text-black p-6 rounded-lg">
                        <h4 class="text-xl font-bold mb-4 text-blue-900">{{ $etecInfo['name'] }}</h4>
                        <div class="space-y-2 text-gray-700">
                            <p><strong>Endereço:</strong><br>{{ $etecInfo['address'] }}</p>
                            <p><strong>CEP:</strong> {{ $etecInfo['cep'] }}</p>
                            <p><strong>Telefone:</strong> {{ $etecInfo['phone'] }}</p>
                            <p><strong>Site:</strong> <a href="{{ $etecInfo['site'] }}" target="_blank" class="text-blue-600 hover:underline">{{ $etecInfo['site'] }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Parceiros -->
    <section id="parceiros" class="py-20 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-blue-900">Parceiros</h2>
            <div class="text-center text-gray-500">
                <p class="text-lg">Parceiros serão anunciados em breve.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">GESTEC 2025</h3>
                    <p class="text-gray-300">Evento de Tecnologia e Inovação</p>
                    <p class="text-gray-300">Organizado pela Etec Antonio Devisate</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Links Úteis</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-orange-400 transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition">Política de Privacidade</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-orange-400 transition">Área Restrita</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Contato</h3>
                    <p class="text-gray-300">{{ $etecInfo['phone'] }}</p>
                    <p class="text-gray-300"><a href="mailto:contato@gestec.com.br" class="hover:text-orange-400 transition">contato@gestec.com.br</a></p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p>&copy; 2025 GESTEC. Todos os direitos reservados.</p>
                <p class="mt-2">Desenvolvido por PitchDev</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>