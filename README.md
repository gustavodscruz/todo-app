# Todo App API

## Visão Geral

API RESTful para gerenciamento de tarefas (TODOs) desenvolvida em Laravel, utilizando JWT para autenticação, padrões Service/Repository e testes automatizados com Pest.

## Funcionalidades

- **Autenticação JWT**: Register, Login, Refresh, Logout
- **TodoLists**: CRUD completo de listas de tarefas
- **Todos**: CRUD completo de tarefas individuais
- **Relacionamentos**: Todos pertencem a TodoLists
- **Filtros**: Busca por tarefas pendentes, completas e arquivadas
- **Validação**: Requests customizados para todos os endpoints
- **Testes**: Cobertura completa com testes de feature

## Tecnologias

- **Laravel 11.x**
- **PHP 8.2+**
- **SQLite** (desenvolvimento/testes)
- **JWT Authentication** (tymon/jwt-auth)
- **Pest** (testes)
- **Repository Pattern**
- **Service Layer Pattern**
- **API Resources** (transformação de dados)

## Estrutura do Projeto

```
app/
├── Http/
│   ├── Controllers/Api/V1/
│   │   ├── AuthController.php
│   │   ├── TodoListController.php
│   │   └── TodoController.php
│   ├── Requests/Api/V1/
│   └── Resources/Api/
├── Models/
│   ├── User.php
│   ├── TodoList.php
│   └── Todo.php
├── Services/
│   ├── Contracts/
│   └── Concretes/
├── Repositories/
│   ├── Base/
│   ├── User/
│   ├── TodoList/
│   └── Todo/
└── Providers/
```

## API Endpoints

### Autenticação
| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/api/v1/auth/register` | Registrar usuário |
| POST | `/api/v1/auth/login` | Login |
| GET | `/api/v1/auth/me` | Dados do usuário autenticado |
| GET | `/api/v1/auth/refresh` | Renovar token |
| GET | `/api/v1/auth/logout` | Logout |

### Todo Lists
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/v1/todo-lists` | Listar todo-lists do usuário |
| POST | `/api/v1/todo-lists` | Criar nova todo-list |
| GET | `/api/v1/todo-lists/{id}` | Buscar todo-list por ID |
| PUT | `/api/v1/todo-lists/{id}` | Atualizar todo-list |
| DELETE | `/api/v1/todo-lists/{id}` | Deletar todo-list |

### Todos
| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/api/v1/todos` | Criar nova tarefa |
| GET | `/api/v1/todos/pending` | Listar tarefas pendentes |
| GET | `/api/v1/todos/{id}` | Buscar tarefa por ID |
| PUT | `/api/v1/todos/{id}` | Atualizar tarefa |
| DELETE | `/api/v1/todos/{id}` | Deletar tarefa |

## Exemplos de Uso

### Registrar Usuário
```bash
curl -X POST "http://localhost:8000/api/v1/auth/register" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva",
    "email": "joao@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Criar TodoList
```bash
curl -X POST "http://localhost:8000/api/v1/todo-lists" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Lista de Compras",
    "description": "Itens para comprar no supermercado",
    "priority": "medium",
    "due_date": "2025-12-31T23:59:59Z"
  }'
```

### Criar Todo
```bash
curl -X POST "http://localhost:8000/api/v1/todos" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Comprar leite",
    "description": "2 litros de leite integral",
    "todo_list_id": 1,
    "priority": "low",
    "due_date": "2025-10-15T14:30:00Z"
  }'
```

## Validações

### TodoList
- `title`: obrigatório, string, máximo 255 caracteres
- `description`: opcional, string
- `priority`: opcional, enum: low, medium, high, urgent
- `due_date`: opcional, data válida

### Todo
- `title`: obrigatório, string, máximo 255 caracteres
- `description`: opcional, string
- `todo_list_id`: obrigatório, ID válido de TodoList
- `priority`: opcional, enum: low, medium, high, urgent
- `due_date`: opcional, data válida
- `is_completed`: opcional, boolean

## Testes

O projeto inclui testes automatizados para todos os endpoints:

```bash
# Executar todos os testes
php artisan test

# Executar testes específicos
php artisan test --filter AuthControllerTest
php artisan test --filter TodoListControllerTest
php artisan test --filter TodoControllerTest

```

### Cobertura de Testes

#### AuthController
- ✅ Registro de usuário
- ✅ Login
- ✅ Buscar dados do usuário autenticado
- ✅ Refresh token
- ✅ Logout

#### TodoListController
- ✅ Criar todo-list
- ✅ Listar todo-lists do usuário
- ✅ Buscar todo-list por ID (com relacionamento todos)
- ✅ Atualizar todo-list
- ✅ Deletar todo-list
- ✅ Validação de propriedade (somente owner)

#### TodoController
- ✅ Criar todo
- ✅ Buscar todo por ID
- ✅ Atualizar todo (com lógica de completed_at)
- ✅ Deletar todo
- ✅ Listar tarefas pendentes
- ✅ Validação de relacionamentos

## Regras de Negócio

### Completed At
- Quando `is_completed` é definido como `true`, `completed_at` é automaticamente preenchido
- Quando `is_completed` é definido como `false`, `completed_at` é definido como `null`

### Autorização
- Usuários só podem acessar suas próprias TodoLists e Todos
- Middleware `auth:api` protege todas as rotas (exceto register/login)

### Relacionamentos
- Todos pertencem a uma TodoList (`todo_list_id`)
- TodoLists pertencem a um User (`user_id`)
- TodoLists podem ter muitos Todos

## Configuração

### Ambiente
```bash
# Copiar arquivo de ambiente
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate

# Gerar chave JWT
php artisan jwt:secret

# Executar migrations
php artisan migrate

# (Opcional) Executar seeders
php artisan db:seed
```

## Arquitetura

### Padrões Implementados
- **Repository Pattern**: Abstração do acesso a dados
- **Service Layer**: Lógica de negócio centralizada
- **API Resources**: Transformação consistente de dados
- **Request Validation**: Validação centralizada e reutilizável
- **Dependency Injection**: Injeção de dependências via Service Container

### Contracts e Concretes

O projeto utiliza interfaces (Contracts) para definir contratos e implementações concretas (Concretes) para manter baixo acoplamento e alta testabilidade.

#### Service Layer
```
Services/
├── Contracts/           # Interfaces dos serviços
│   ├── AuthServiceInterface.php
│   ├── UserServiceInterface.php
│   ├── TodoListServiceInterface.php
│   └── TodoServiceInterface.php
└── Concretes/           # Implementações concretas
    ├── AuthService.php
    ├── UserService.php
    ├── TodoListService.php
    └── TodoService.php
```

**Exemplo de Contract:**
```php
interface TodoListServiceInterface
{
    public function createTodoList(array $data): Model;
    public function getTodoLists(int $userId): Collection;
    public function getTodoListById(int $id): TodoList;
    public function updateTodoList(int $id, array $data): Model;
    public function deleteTodoList(int $id): bool;
}
```

**Exemplo de Concrete:**
```php
class TodoListService implements TodoListServiceInterface
{
    public function __construct(
        protected TodoListRepositoryInterface $repository
    ) {}

    public function createTodoList(array $data): Model
    {
        return $this->repository->create($data);
    }
    // ... demais implementações
}
```

#### Repository Layer
```
Repositories/
├── Base/
│   ├── Contracts/
│   │   ├── RepositoryInterface.php
│   │   └── QueryableRepositoryInterface.php
│   └── Concretes/
│       ├── Repository.php
│       └── QueryableRepository.php
├── User/
│   ├── Contracts/
│   │   └── UserRepositoryInterface.php
│   └── Concretes/
│       └── UserRepository.php
├── TodoList/
│   ├── Contracts/
│   │   └── TodoListRepositoryInterface.php
│   └── Concretes/
│       └── TodoListRepository.php
└── Todo/
    ├── Contracts/
    │   └── TodoRepositoryInterface.php
    └── Concretes/
        └── TodoRepository.php
```

**Exemplo de Repository Contract:**
```php
interface TodoListRepositoryInterface extends RepositoryInterface
{
    public function findByUser(int $userId): Collection;
    public function findWithTodos(int $id): ?Model;
}
```

**Exemplo de Repository Concrete:**
```php
class TodoListRepository extends QueryableRepository implements TodoListRepositoryInterface
{
    public function __construct(TodoList $model)
    {
        parent::__construct($model);
    }

    public function findByUser(int $userId): Collection
    {
        return $this->query()->where('user_id', $userId)->get();
    }

    public function findWithTodos(int $id): ?Model
    {
        return $this->query()->with('todos')->find($id);
    }
}
```

#### Service Provider Bindings

O `ServiceClassProvider` registra os bindings entre interfaces e implementações:

```php
class ServiceClassProvider extends BaseServiceProvider
{
    public function register(): void
    {
        // Service bindings
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(TodoListServiceInterface::class, TodoListService::class);
        $this->app->bind(TodoServiceInterface::class, TodoService::class);
    }
}
```

Similarmente, o `RepositoryServiceProvider` registra os repositories:

```php
class RepositoryServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        // Repository bindings
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TodoListRepositoryInterface::class, TodoListRepository::class);
        $this->app->bind(TodoRepositoryInterface::class, TodoRepository::class);
    }
}
```

### Models e Relacionamentos

```php
// User (1:N) TodoList (1:N) Todo
User::class
├── hasMany(TodoList::class)
└── hasMany(Todo::class)

TodoList::class
├── belongsTo(User::class)
└── hasMany(Todo::class)

Todo::class
├── belongsTo(User::class)
└── belongsTo(TodoList::class)
```

### Database Schema

#### users
- id, name, email, password, timestamps

#### todo_lists  
- id, title, description, priority, due_date, is_completed, is_archived, completed_at, user_id, timestamps

#### todos
- id, title, description, priority, due_date, is_completed, completed_at, user_id, todo_list_id, timestamps

## Instalação e Configuração

### Pré-requisitos
- PHP 8.2+
- Composer
- SQLite (desenvolvimento) / MySQL (produção)

### Setup

```bash
# Clonar repositório
git clone <repository-url>
cd todo-app

# Instalar dependências
composer install

# Configurar ambiente
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

# Executar migrations
php artisan migrate

# (Opcional) Executar seeders
php artisan db:seed

# Iniciar servidor
php artisan serve
```

### JWT Configuration
O projeto utiliza JWT para autenticação. Configure as seguintes variáveis no `.env`:

```env
JWT_SECRET=your-secret-key
JWT_TTL=60
JWT_REFRESH_TTL=20160
```

## Desenvolvimento

### Comandos Úteis
```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Verificar rotas
php artisan route:list

# Executar servidor local
php artisan serve

```

### Debugging
O projeto inclui logs detalhados. Para debugar:

```bash
# Ver logs em tempo real
tail -f storage/logs/laravel.log

# Usar logger nos controllers
logger()->debug('Debug message', ['data' => $variable]);
```