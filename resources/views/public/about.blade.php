<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTEC - Sistema de Gerenciamento de Eventos Técnicos</title>
    <meta name="description" content="GESTEC - Sistema completo para gerenciamento de eventos técnicos de gestão e desenvolvimento de sistemas">
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('storage/logo.svg') }}" alt="GESTEC Logo" style="height: 48px; width: auto;">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">GESTEC</h1>
                        <p class="text-sm text-gray-600">Sistema de Gerenciamento de Eventos Técnicos</p>
                    </div>
                </div>
                <nav class="flex space-x-4">
                    <a href="{{ route('public.about') }}" class="text-blue-600 hover:text-blue-800 font-medium">Sobre</a>
                    <a href="{{ route('events.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Gerenciar Eventos</a>
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-800">Início</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="py-20 px-4">
        <div class="container mx-auto text-center">
            <h2 class="text-5xl font-bold text-gray-900 mb-6">
                GESTEC
            </h2>
            <p class="text-xl text-gray-600 mb-4">
                Sistema de Gerenciamento de Eventos Técnicos de Gestão e Desenvolvimento de Sistemas
            </p>
            <p class="text-lg text-gray-500 max-w-3xl mx-auto">
                Uma solução completa e moderna para organizar, gerenciar e controlar eventos técnicos
                relacionados à gestão e desenvolvimento de sistemas de informação.
            </p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center text-gray-900 mb-12">Funcionalidades Principais</h3>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="bg-blue-600 text-white w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Criar Eventos</h4>
                    <p class="text-gray-600">Cadastre novos eventos com todas as informações necessárias: título, descrição, data, local, capacidade e preço.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="bg-green-600 text-white w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Gerenciar Eventos</h4>
                    <p class="text-gray-600">Visualize todos os eventos em uma interface organizada, com opções de edição e exclusão.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="bg-purple-600 text-white w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Visualizar Detalhes</h4>
                    <p class="text-gray-600">Veja informações completas de cada evento, incluindo todos os dados cadastrados.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="bg-yellow-600 text-white w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Editar Eventos</h4>
                    <p class="text-gray-600">Atualize informações dos eventos existentes de forma rápida e intuitiva.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="bg-red-600 text-white w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Excluir Eventos</h4>
                    <p class="text-gray-600">Remova eventos que não são mais necessários com confirmação de segurança.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                    <div class="bg-indigo-600 text-white w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Interface Intuitiva</h4>
                    <p class="text-gray-600">Interface moderna e responsiva, totalmente em português brasileiro.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Technologies Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center text-gray-900 mb-12">Tecnologias Utilizadas</h3>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Laravel -->
                <div class="bg-white p-6 rounded-xl shadow-lg text-center hover:shadow-xl transition duration-300">
                    <div class="bg-red-500 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.796 5.835c.67.725 1.032 1.617 1.15 2.586.286 2.357-.804 4.308-2.769 5.103-.493.2-.859.756-.859 1.338v2.586c0 .552-.447 1-1 1s-1-.448-1-1v-2.586c0-1.104.715-2.055 1.756-2.441 1.536-.567 2.733-2.032 2.546-3.793-.123-.907-.54-1.737-1.15-2.386-.64-.679-1.49-1.041-2.4-1.041-.91 0-1.76.362-2.4 1.041-.61.649-1.027 1.479-1.15 2.386-.187 1.761.982 3.226 2.518 3.793.938.346 1.756 1.297 1.756 2.441v2.586c0 .552-.447 1-1 1s-1-.448-1-1v-2.586c0-.582-.366-1.138-.859-1.338-1.965-.795-3.055-2.746-2.769-5.103.118-.969.48-1.861 1.15-2.586.64-.679 1.49-1.041 2.4-1.041.91 0 1.76.362 2.4 1.041z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Laravel 12</h4>
                    <p class="text-gray-600">Framework PHP robusto e elegante para desenvolvimento web moderno.</p>
                </div>

                <!-- MySQL -->
                <div class="bg-white p-6 rounded-xl shadow-lg text-center hover:shadow-xl transition duration-300">
                    <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">MySQL</h4>
                    <p class="text-gray-600">Banco de dados relacional confiável para armazenamento seguro dos dados.</p>
                </div>

                <!-- Tailwind CSS -->
                <div class="bg-white p-6 rounded-xl shadow-lg text-center hover:shadow-xl transition duration-300">
                    <div class="bg-teal-500 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 6c-2.67 0-4.33 1.33-5 4 1-1.33 2.17-2.83 4.5-2.83.76 0 1.47.11 2.13.33L12 10.17 9.87 6.5C10.5 6.17 11.22 6 12 6z"/>
                            <path d="M12 6c2.67 0 4.33 1.33 5 4-1-1.33-2.17-2.83-4.5-2.83-.76 0-1.47.11-2.13.33L12 10.17l2.13-3.67c-.63-.33-1.35-.5-2.13-.5z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Tailwind CSS</h4>
                    <p class="text-gray-600">Framework CSS utilitário para interfaces modernas e responsivas.</p>
                </div>

                <!-- Vite -->
                <div class="bg-white p-6 rounded-xl shadow-lg text-center hover:shadow-xl transition duration-300">
                    <div class="bg-yellow-500 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.5 2.5l-7 7v11h11l7-7V2.5H8.5z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-2">Vite</h4>
                    <p class="text-gray-600">Ferramenta de build rápida para desenvolvimento frontend moderno.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h3 class="text-3xl font-bold text-gray-900 mb-8">Sobre o GESTEC</h3>
                <div class="grid md:grid-cols-2 gap-8 text-left">
                    <div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-4">Objetivo</h4>
                        <p class="text-gray-600 mb-6">
                            O GESTEC foi desenvolvido para facilitar o gerenciamento de eventos técnicos relacionados
                            à gestão e desenvolvimento de sistemas. Nossa missão é fornecer uma ferramenta completa,
                            intuitiva e eficiente para organizadores de eventos técnicos.
                        </p>

                        <h4 class="text-xl font-semibold text-gray-900 mb-4">Público-Alvo</h4>
                        <p class="text-gray-600">
                            Destinado a profissionais de TI, gestores de sistemas, estudantes de tecnologia,
                            empresas de desenvolvimento de software e instituições de ensino que organizam
                            eventos técnicos, workshops, palestras e conferências.
                        </p>
                    </div>

                    <div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-4">Benefícios</h4>
                        <ul class="text-gray-600 space-y-2 mb-6">
                            <li>• Interface totalmente em português brasileiro</li>
                            <li>• Design moderno e responsivo</li>
                            <li>• Gerenciamento completo de eventos</li>
                            <li>• Validação de dados automática</li>
                            <li>• Confirmações de ações importantes</li>
                            <li>• Formatação brasileira (datas e moedas)</li>
                        </ul>

                        <h4 class="text-xl font-semibold text-gray-900 mb-4">Segurança</h4>
                        <p class="text-gray-600">
                            Implementa as melhores práticas de segurança do Laravel, incluindo proteção
                            contra CSRF, validação de entrada e sanitização de dados.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-700">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-3xl font-bold text-white mb-4">Comece a Gerenciar Seus Eventos Agora!</h3>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Experimente gratuitamente o GESTEC e veja como é fácil organizar seus eventos técnicos.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('events.index') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
                    Gerenciar Eventos
                </a>
                <a href="{{ url('/') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-200">
                    Página Inicial
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <div class="flex items-center justify-center space-x-4 mb-4">
                <div class="bg-blue-600 text-white p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="text-lg font-semibold">GESTEC</h4>
                    <p class="text-sm text-gray-400">Sistema de Gerenciamento de Eventos Técnicos</p>
                </div>
            </div>
            <p class="text-gray-400">
                © 2025 GESTEC. Desenvolvido com Laravel, MySQL e Tailwind CSS.
            </p>
        </div>
    </footer>
</body>
</html>