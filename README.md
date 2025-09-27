# Sistema de Gerenciamento de Eventos - GESTEC

Este é um sistema completo de gerenciamento de eventos desenvolvido com Laravel, MySQL e Tailwind CSS.

## Funcionalidades

### 🎯 Gerenciamento de Eventos
- Listar eventos
- Criar novos eventos
- Visualizar detalhes de eventos
- Editar eventos existentes
- Excluir eventos
- Eventos com data/hora de início e fim

### 👥 Sistema de Usuários e Papéis
- Sistema de autenticação completo
- Papéis de usuário: Administrador, Organizador, Ministrador, Palestrante, Colaborador, Participante, Parceiro
- Qualquer usuário pode ser responsável por atividades (ministradores e palestrantes)
- Perfil completo com CPF, WhatsApp, contatos adicionais

### 📚 Gerenciamento de Atividades
- Criar atividades (palestras, minicursos, workshops, painéis)
- Vincular atividades a eventos
- Sistema de inscrição automática em atividades quando usuário se inscreve no evento
- Controle de vagas e lista de espera
- Carga horária e custo opcional
- Upload de comprovantes para atividades pagas

### 🎫 Sistema de Inscrições
- Inscrição automática em todas as atividades de um evento
- Controle de status: confirmado, lista de espera, cancelado
- Validação de presença
- Confirmação de pagamento

### 🏆 Sistema de Certificados
- Geração automática de certificados
- Templates personalizáveis
- Assinaturas digitais (diretor e coordenador)
- Validação online de certificados
- Códigos únicos de validação

### ⚙️ Configurações Avançadas
- Configurações globais do sistema
- Sistema de temas (claro/escuro)
- Personalização da homepage
- Configurações de certificados
- Upload de logos e assinaturas

### 🎨 Interface e UX
- Design responsivo com Tailwind CSS
- Sistema de temas claro/escuro
- Interface intuitiva para administração
- Homepage personalizável

## Tecnologias Utilizadas

- **Laravel 12**: Framework PHP para desenvolvimento web
- **MySQL**: Banco de dados relacional
- **Tailwind CSS**: Framework CSS para estilização
- **Vite**: Ferramenta de build para assets
- **Alpine.js**: Framework JavaScript para interatividade
- **File Storage**: Sistema de armazenamento de arquivos (logos, assinaturas, comprovantes)

## Instalação

1. Clone o repositório
2. Instale as dependências do PHP:
   ```bash
   composer install
   ```
3. Instale as dependências do Node.js:
   ```bash
   npm install
   ```
4. Configure o arquivo `.env` com as credenciais do banco de dados
5. Execute as migrações:
   ```bash
   php artisan migrate
   ```
6. Configure o storage link:
   ```bash
   php artisan storage:link
   ```
7. Compile os assets:
   ```bash
   npm run build
   ```
8. Inicie o servidor:
   ```bash
   php artisan serve
   ```

## Estrutura do Banco de Dados

### Tabela `users`
- `id`: ID único do usuário
- `name`: Nome do usuário
- `full_name`: Nome completo
- `email`: Email único
- `email_verified_at`: Data de verificação do email
- `password`: Senha criptografada
- `role`: Papel do usuário (administrador, organizador, ministrador, palestrante, colaborador, participante, parceiro)
- `cpf`: CPF único (14 caracteres)
- `whatsapp`: Número do WhatsApp
- `additional_contacts`: Contatos adicionais (JSON)
- `person_type`: Tipo de pessoa (pf/pj)
- `signature_image`: Caminho da imagem da assinatura
- `remember_token`: Token de lembrança
- `created_at`: Data de criação
- `updated_at`: Data de atualização

### Tabela `events`
- `id`: ID único do evento
- `title`: Título do evento
- `description`: Descrição do evento (opcional)
- `start_date`: Data e hora de início do evento
- `end_date`: Data e hora de fim do evento (opcional)
- `location`: Local do evento (opcional)
- `capacity`: Capacidade máxima (opcional)
- `price`: Preço do evento (padrão 0)
- `created_at`: Data de criação
- `updated_at`: Data de atualização

### Tabela `activities`
- `id`: ID único da atividade
- `event_id`: ID do evento (opcional, nullable)
- `name`: Nome da atividade
- `description`: Descrição da atividade (opcional)
- `instructor_id`: ID do instrutor (ministrador ou palestrante)
- `start_time`: Data e hora de início
- `end_time`: Data e hora de fim
- `workload_hours`: Carga horária em horas
- `total_spots`: Número total de vagas
- `has_cost`: Se a atividade tem custo
- `pix_key`: Chave PIX para pagamento (opcional)
- `proof_file_path`: Caminho do arquivo de comprovante (opcional)
- `waiting_list_enabled`: Lista de espera habilitada (padrão true)
- `type`: Tipo da atividade (palestra, minicurso, workshop, painel)
- `created_at`: Data de criação
- `updated_at`: Data de atualização

### Tabela `event_enrollments`
- `id`: ID único da inscrição
- `user_id`: ID do usuário
- `event_id`: ID do evento
- `status`: Status da inscrição (confirmed, waiting_list, cancelled)
- `enrolled_at`: Data e hora da inscrição
- `created_at`: Data de criação
- `updated_at`: Data de atualização

### Tabela `enrollments`
- `id`: ID único da inscrição em atividade
- `user_id`: ID do usuário
- `activity_id`: ID da atividade
- `status`: Status da inscrição (confirmed, waiting_list, cancelled)
- `proof_file_path`: Caminho do arquivo de comprovante (opcional)
- `payment_confirmed`: Confirmação de pagamento (padrão false)
- `enrolled_at`: Data e hora da inscrição
- `created_at`: Data de criação
- `updated_at`: Data de atualização

### Tabela `presences`
- `id`: ID único da presença
- `enrollment_id`: ID da inscrição
- `validated_by`: ID do usuário que validou
- `validated_at`: Data e hora da validação
- `created_at`: Data de criação
- `updated_at`: Data de atualização

### Tabela `certificates`
- `id`: ID único do certificado
- `user_id`: ID do usuário
- `activity_id`: ID da atividade (opcional)
- `certificate_type`: Tipo do certificado (participant, collaborator, instructor, organizer, partner)
- `total_hours`: Total de horas (opcional)
- `validation_code`: Código único de validação (32 caracteres)
- `pdf_path`: Caminho do arquivo PDF (opcional)
- `certificate_data`: Dados do certificado (JSON, opcional)
- `issued_at`: Data de emissão
- `created_at`: Data de criação
- `updated_at`: Data de atualização

### Tabela `certificate_templates`
- `id`: ID único do template
- `name`: Nome do template
- `description`: Descrição (opcional)
- `template_image_path`: Caminho da imagem do template
- `type`: Tipo (global, activity_specific)
- `activity_id`: ID da atividade (opcional)
- `is_default`: Se é o template padrão
- `layout_config`: Configuração de layout (JSON, opcional)
- `created_at`: Data de criação
- `updated_at`: Data de atualização

### Tabela `global_settings`
- `id`: ID único da configuração
- `project_name`: Nome do projeto (padrão GESTEC)
- `logo_path`: Caminho do logo (opcional)
- `description`: Descrição (opcional)
- `theme`: Tema (light/dark, padrão light)
- `homepage_title`: Título da homepage (padrão 'Bem-vindo ao GESTEC')
- `homepage_subtitle`: Subtítulo da homepage (opcional)
- `homepage_description`: Descrição da homepage (opcional)
- `homepage_features`: Recursos da homepage (JSON, opcional)
- `homepage_cta_text`: Texto do botão CTA (padrão 'Começar Agora')
- `homepage_cta_link`: Link do botão CTA (padrão '/dashboard')
- `certificate_director_signature`: Assinatura do diretor (opcional)
- `certificate_coordinator_signature`: Assinatura do coordenador (opcional)
- `certificate_text_template`: Template de texto do certificado (opcional)
- `validation_base_url`: URL base para validação (padrão 'https://pitchdev.com.br/validacao')
- `created_at`: Data de criação
- `updated_at`: Data de atualização

## Rotas Principais

### Eventos
- `GET /events`: Lista todos os eventos
- `GET /events/create`: Formulário para criar evento
- `POST /events`: Salvar novo evento
- `GET /events/{id}`: Visualizar evento específico
- `GET /events/{id}/edit`: Formulário para editar evento
- `PUT /events/{id}`: Atualizar evento
- `DELETE /events/{id}`: Excluir evento

### Atividades
- `GET /activities`: Lista todas as atividades
- `GET /activities/create`: Formulário para criar atividade
- `POST /activities`: Salvar nova atividade
- `GET /activities/{id}`: Visualizar atividade específica
- `GET /activities/{id}/edit`: Formulário para editar atividade
- `PUT /activities/{id}`: Atualizar atividade
- `DELETE /activities/{id}`: Excluir atividade

### Administração
- `GET /admin/dashboard`: Dashboard administrativo
- `GET /admin/users`: Gerenciamento de usuários
- `GET /admin/settings`: Configurações globais
- `GET /admin/certificates`: Gerenciamento de certificados

### Inscrições
- `POST /events/{id}/enroll`: Inscrever-se em evento
- `POST /activities/{id}/enroll`: Inscrever-se em atividade
- `POST /presences/{enrollmentId}/validate`: Validar presença

## Desenvolvimento

Para desenvolvimento, use:
```bash
npm run dev
```

Isso irá iniciar o Vite em modo de desenvolvimento com hot reload.

## Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
