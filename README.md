# Sistema de Gerenciamento de Eventos

Este é um sistema de gerenciamento de eventos desenvolvido com Laravel, MySQL e Tailwind CSS.

## Funcionalidades

- Listar eventos
- Criar novos eventos
- Visualizar detalhes de eventos
- Editar eventos existentes
- Excluir eventos

## Tecnologias Utilizadas

- **Laravel 12**: Framework PHP para desenvolvimento web
- **MySQL**: Banco de dados relacional
- **Tailwind CSS**: Framework CSS para estilização
- **Vite**: Ferramenta de build para assets

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
6. Compile os assets:
   ```bash
   npm run build
   ```
7. Inicie o servidor:
   ```bash
   php artisan serve
   ```

## Estrutura do Banco de Dados

### Tabela `events`

- `id`: ID único do evento
- `title`: Título do evento
- `description`: Descrição do evento (opcional)
- `event_date`: Data e hora do evento
- `location`: Local do evento (opcional)
- `capacity`: Capacidade máxima (opcional)
- `price`: Preço do evento (padrão 0)
- `created_at`: Data de criação
- `updated_at`: Data de atualização

## Rotas

- `GET /events`: Lista todos os eventos
- `GET /events/create`: Formulário para criar evento
- `POST /events`: Salvar novo evento
- `GET /events/{id}`: Visualizar evento específico
- `GET /events/{id}/edit`: Formulário para editar evento
- `PUT /events/{id}`: Atualizar evento
- `DELETE /events/{id}`: Excluir evento

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
