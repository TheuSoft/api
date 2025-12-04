# API RESTful - Sistema de Feedback de Produtos

API RESTful desenvolvida em PHP puro seguindo padrão MVC com arquitetura em camadas (Controller → Service → DAO). Sistema completo de gerenciamento de produtos, usuários e feedbacks com autenticação JWT, roteamento dinâmico e validações robustas.

## 🚀 Características

- ✅ **Autenticação JWT** com rotas protegidas e públicas
- ✅ **Padrão MVC** com separação de responsabilidades
- ✅ **Arquitetura em camadas**: Controller → Service → DAO
- ✅ **CRUD completo** para todas as entidades
- ✅ **Roteamento dinâmico** via parâmetro `?param=`
- ✅ **Reflection API** para injeção automática de parâmetros
- ✅ **Respostas padronizadas** em JSON (UTF-8, Pretty Print)
- ✅ **Validação de dados** com mensagens descritivas
- ✅ **Interface DAO** para abstração do banco de dados
- ✅ **Factory Pattern** e **Singleton Pattern**
- ✅ **Autoload** de classes com namespaces PSR-4
- ✅ **Prepared Statements** com PDO para segurança SQL
- ✅ **Suporte a múltiplos verbos HTTP** (GET, POST, PUT, DELETE)

## 🏗️ Arquitetura

### Camadas da Aplicação

```
┌─────────────────────────────────────────┐
│  index.php → Controller → Rotas         │  ← Ponto de entrada via ?param=
├─────────────────────────────────────────┤
│  Acao (Reflection + JWT + Injeção)      │  ← Executa método do controller
├─────────────────────────────────────────┤
│  Controller (Produto, Usuario, etc.)    │  ← Validações e coordenação
├─────────────────────────────────────────┤
│  Service (Regras de negócio)            │  ← Lógica de aplicação
├─────────────────────────────────────────┤
│  DAO (Acesso a dados via PDO)           │  ← Queries e persistência
├─────────────────────────────────────────┤
│  MySQL DB (feedback_produtos)           │  ← Banco de dados
└─────────────────────────────────────────┘
```

### Padrões de Projeto

- **MVC (Model-View-Controller)** - Separação de responsabilidades
- **DAO (Data Access Object)** - Interfaces IProdutoDAO, IUsuarioDAO, IFeedbackDAO
- **Factory Pattern** - MysqlFactory injeta conexão nos DAOs
- **Singleton Pattern** - MysqlSingleton gerencia conexão PDO única
- **Reflection** - Acao injeta parâmetros automaticamente nos métodos
- **JWT (JSON Web Token)** - Autenticação stateless via JwtAuth
- **Front Controller** - index.php centraliza todas as requisições

## 📦 Instalação

### Pré-requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Apache (XAMPP, WAMP, ou similar)

### Passos

1. Clone o repositório na pasta htdocs do XAMPP:
```bash
cd C:\xampp\htdocs
git clone <seu-repositorio> api
```

2. Configure o banco de dados executando o arquivo `banco.sql`:
```bash
mysql -u root -p < banco.sql
```

3. Configure a conexão com o banco em `generic/MysqlSingleton.php`:
```php
private function __construct()
{
    $this->conexao = new PDO(
        'mysql:host=localhost;dbname=feedback_produtos;charset=utf8mb4',
        'root',  // usuário
        '',      // senha
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
}
```

4. Acesse a API:
```
http://localhost/api/index.php?param=produto
```

## 🗄️ Configuração do Banco de Dados

Execute o script SQL localizado em `banco.sql`:

```sql
-- Produtos
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    preco DECIMAL(10, 2) NOT NULL
);

-- Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

-- Feedback
CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    produto_id INT,
    nota INT NOT NULL CHECK (nota BETWEEN 1 AND 5),
    comentario TEXT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);
```

## 📁 Estrutura do Projeto

```
api/
├── controller/          # Controllers (recebem requisições HTTP)
│   ├── Auth.php         # Autenticação (login)
│   ├── Produto.php      # CRUD de produtos
│   ├── Usuario.php      # CRUD de usuários
│   └── Feedback.php     # CRUD de feedbacks
├── service/             # Services (regras de negócio)
│   ├── ProdutoService.php
│   ├── UsuarioService.php  # Inclui autenticação JWT
│   └── FeedbackService.php
├── dao/                 # Data Access Objects
│   ├── IProdutoDAO.php
│   ├── IUsuarioDAO.php
│   ├── IFeedbackDAO.php
│   └── mysql/           # Implementações MySQL
│       ├── ProdutoDAO.php
│       ├── UsuarioDAO.php
│       └── FeedbackDAO.php
├── generic/             # Classes genéricas (arquitetura)
│   ├── Autoload.php     # Autoloader PSR-4
│   ├── Controller.php   # Controller principal (JSON response)
│   ├── Rotas.php        # Mapeamento de rotas
│   ├── Acao.php         # Executor de endpoints (Reflection)
│   ├── Endpoint.php     # Definição de endpoint (classe, método, autenticar)
│   ├── JwtAuth.php      # Geração e validação de JWT
│   ├── Retorno.php      # Envelope de resposta
│   ├── MysqlFactory.php # Factory de DAOs
│   └── MysqlSingleton.php # Singleton PDO
├── index.php            # Ponto de entrada (Front Controller)
├── banco.sql            # Script de criação do banco
├── composer.json        # Dependências (firebase/php-jwt)
└── README.md            # Documentação
```

## 🔐 Autenticação JWT

### Como Funciona

1. **Login**: POST em `auth/login` retorna um token JWT
2. **Rotas Protegidas**: Envie o token no header `Authorization: Bearer TOKEN`
3. **Validação**: JwtAuth valida o token antes de executar o método do controller

### Obter Token

```http
POST /index.php?param=auth/login
Content-Type: application/json

{
  "email": "usuario@exemplo.com",
  "senha": "123456"
}
```

**Resposta:**
```json
{
  "erro": null,
  "dados": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  }
}
```

### Usar Token em Rotas Protegidas

**Header:**
```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

**No Postman:**
- Aba **Authorization** → Type: **Bearer Token** → Cole o token

## 📡 Endpoints da API

### Base URL
```
http://localhost/api/index.php?param=
```

### Tabela Resumida de Rotas

| Rota | Método | Autenticação | Descrição |
|------|--------|--------------|-----------|
| `auth/login` | POST | ❌ Pública | Autenticar usuário e obter JWT |
| `produto` | GET | ✅ Protegida | Listar todos os produtos |
| `produto` | POST | ✅ Protegida | Criar novo produto |
| `produto` | PUT | ✅ Protegida | Atualizar produto |
| `produto` | DELETE | ✅ Protegida | Deletar produto |
| `produto/buscar` | GET | ✅ Protegida | Buscar produto por ID |
| `usuario` | GET | ✅ Protegida | Listar todos os usuários |
| `usuario` | POST | ❌ Pública | Cadastrar novo usuário |
| `usuario` | PUT | ✅ Protegida | Atualizar usuário |
| `usuario` | DELETE | ✅ Protegida | Deletar usuário |
| `usuario/buscar` | GET | ✅ Protegida | Buscar usuário por ID |
| `feedback` | GET | ✅ Protegida | Listar todos os feedbacks |
| `feedback` | POST | ✅ Protegida | Criar novo feedback |
| `feedback` | DELETE | ✅ Protegida | Deletar feedback |
| `feedback/buscar` | GET | ✅ Protegida | Buscar feedback por ID |
| `feedback/produto` | GET | ✅ Protegida | Listar feedbacks por produto |
| `feedback/usuario` | GET | ✅ Protegida | Listar feedbacks por usuário |

---

## 🔑 Autenticação

### Login
```http
POST /index.php?param=auth/login
Content-Type: application/json

{
  "email": "usuario@exemplo.com",
  "senha": "123456"
}
```

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJub21lIjoiSm9cdTAwZTNvIFNpbHZhIiwiZW1haWwiOiJ1c3VhcmlvQGV4ZW1wbG8uY29tIn0.xyz..."
  }
}
```

**Resposta de Erro (401):**
```json
{
  "erro": null,
  "dados": {
    "erro": "Não autorizado"
  }
}
```

---

## 🛍️ Produtos

### Listar Todos os Produtos
```http
GET /index.php?param=produto
Authorization: Bearer SEU_TOKEN_JWT
```

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": [
    {
      "id": 1,
      "nome": "Mouse Gamer Logitech",
      "descricao": "Mouse Gamer Logitech G502 HERO, RGB, 16000 DPI",
      "preco": "250.00"
    },
    {
      "id": 2,
      "nome": "Teclado Mecânico Razer",
      "descricao": "Teclado Mecânico Razer BlackWidow V3, Switch Green, RGB",
      "preco": "450.00"
    }
  ]
}
```

### Buscar Produto por ID
```http
GET /index.php?param=produto/buscar&id=1
Authorization: Bearer SEU_TOKEN_JWT
```

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": [
    {
      "id": 1,
      "nome": "Mouse Gamer Logitech",
      "descricao": "Mouse Gamer Logitech G502 HERO, RGB, 16000 DPI",
      "preco": "250.00"
    }
  ]
}
```

**Resposta de Erro (não encontrado):**
```json
{
  "erro": null,
  "dados": []
}
```

### Criar Produto
```http
POST /index.php?param=produto
Content-Type: application/json
Authorization: Bearer SEU_TOKEN_JWT

{
  "nome": "Monitor LG UltraWide",
  "descricao": "Monitor LG UltraWide 29\" IPS Full HD, 75Hz",
  "preco": 1200.00
}
```

**Validações:**
- `nome`, `descricao` e `preco` são obrigatórios

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": {
    "mensagem": "Dados Salvo com Sucesso!"
  }
}
```

**Resposta de Erro (400):**
```json
{
  "erro": null,
  "dados": {
    "erro": "Campos obrigatórios: nome, descricao, preco"
  }
}
```

### Atualizar Produto
```http
PUT /index.php?param=produto
Content-Type: application/json
Authorization: Bearer SEU_TOKEN_JWT

{
  "id": 1,
  "nome": "Mouse Gamer Logitech G502 HERO",
  "descricao": "Mouse Gamer RGB, 25600 DPI, 11 Botões Programáveis",
  "preco": 280.00
}
```

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": {
    "mensagem": "Alterado com sucesso"
  }
}
```

**Resposta de Erro (não encontrado):**
```json
{
  "erro": null,
  "dados": {
    "erro": "Registro não alterado"
  }
}
```

### Deletar Produto
```http
DELETE /index.php?param=produto&id=1
Authorization: Bearer SEU_TOKEN_JWT
```

**Ou via Body JSON:**
```http
DELETE /index.php?param=produto
Content-Type: application/json
Authorization: Bearer SEU_TOKEN_JWT

{
  "id": 1
}
```

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": {
    "mensagem": "Produto deletado com sucesso"
  }
}
```

**Resposta de Erro (não encontrado):**
```json
{
  "erro": null,
  "dados": {
    "erro": "Registro não encontrado ou não excluído"
  }
}
```

---

## 👥 Usuários

### Listar Todos os Usuários
```http
GET /index.php?param=usuario
Authorization: Bearer SEU_TOKEN_JWT
```

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": [
    {
      "id": 1,
      "nome": "João Silva",
      "email": "joao@exemplo.com"
    }
  ]
}
```

### Buscar Usuário por ID
```http
GET /index.php?param=usuario/buscar&id=1
Authorization: Bearer SEU_TOKEN_JWT
```

### Cadastrar Usuário
```http
POST /index.php?param=usuario
Content-Type: application/json

{
  "nome": "Maria Santos",
  "email": "maria@exemplo.com",
  "senha": "senhaSegura123"
}
```

**Validações:**
- `nome`, `email` e `senha` são obrigatórios
- Email deve ser válido
- Email não pode estar duplicado

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": {
    "mensagem": "Usuário cadastrado com sucesso"
  }
}
```

**Resposta de Erro (email duplicado):**
```json
{
  "erro": null,
  "dados": {
    "erro": "Email já cadastrado"
  }
}
```

**Resposta de Erro (email inválido):**
```json
{
  "erro": null,
  "dados": {
    "erro": "Email inválido"
  }
}
```

### Atualizar Usuário
```http
PUT /index.php?param=usuario
Content-Type: application/json
Authorization: Bearer SEU_TOKEN_JWT

{
  "id": 1,
  "nome": "João Silva Santos",
  "email": "joao.santos@exemplo.com"
}
```

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": {
    "mensagem": "Usuário alterado com sucesso"
  }
}
```

### Deletar Usuário
```http
DELETE /index.php?param=usuario&id=1
Authorization: Bearer SEU_TOKEN_JWT
```

**Ou via Body:**
```http
DELETE /index.php?param=usuario
Content-Type: application/json
Authorization: Bearer SEU_TOKEN_JWT

{
  "id": 1
}
```

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": {
    "mensagem": "Usuário deletado com sucesso"
  }
}
```

---

## ⭐ Feedbacks

### Listar Todos os Feedbacks
```http
GET /index.php?param=feedback
Authorization: Bearer SEU_TOKEN_JWT
```

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": [
    {
      "id": 1,
      "usuario_id": 1,
      "produto_id": 2,
      "nota": 5,
      "comentario": "Produto excelente! Recomendo muito.",
      "data_criacao": "2025-12-04 10:30:00"
    }
  ]
}
```

### Buscar Feedback por ID
```http
GET /index.php?param=feedback/buscar&id=1
Authorization: Bearer SEU_TOKEN_JWT
```

### Listar Feedbacks por Produto
```http
GET /index.php?param=feedback/produto&produto_id=2
Authorization: Bearer SEU_TOKEN_JWT
```

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": [
    {
      "id": 1,
      "usuario_id": 1,
      "produto_id": 2,
      "nota": 5,
      "comentario": "Produto excelente!"
    },
    {
      "id": 3,
      "usuario_id": 5,
      "produto_id": 2,
      "nota": 4,
      "comentario": "Muito bom, vale a pena."
    }
  ]
}
```

### Listar Feedbacks por Usuário
```http
GET /index.php?param=feedback/usuario&usuario_id=1
Authorization: Bearer SEU_TOKEN_JWT
```

### Criar Feedback
```http
POST /index.php?param=feedback
Content-Type: application/json
Authorization: Bearer SEU_TOKEN_JWT

{
  "produto_id": 2,
  "usuario_id": 1,
  "comentario": "Produto de excelente qualidade!",
  "nota": 5
}
```

**Validações:**
- `produto_id`, `usuario_id`, `comentario` e `nota` são obrigatórios
- `nota` deve ser entre 1 e 5
- `produto_id` e `usuario_id` devem existir no banco

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": {
    "mensagem": "Dados Salvo com Sucesso!"
  }
}
```

**Resposta de Erro (campos faltando):**
```json
{
  "erro": null,
  "dados": {
    "erro": "Campos obrigatórios: produto_id, usuario_id, comentario, nota"
  }
}
```

### Deletar Feedback
```http
DELETE /index.php?param=feedback&id=1
Authorization: Bearer SEU_TOKEN_JWT
```

**Ou via Body:**
```http
DELETE /index.php?param=feedback
Content-Type: application/json
Authorization: Bearer SEU_TOKEN_JWT

{
  "id": 1
}
```

**Resposta de Sucesso (200):**
```json
{
  "erro": null,
  "dados": {
    "mensagem": "Feedback deletado com sucesso"
  }
}
```

---

## 💡 Exemplos de Uso

### Postman - Fluxo Completo

#### 1. Cadastrar Usuário (Público)
```http
POST http://localhost/api/index.php?param=usuario
Content-Type: application/json

{
  "nome": "João Silva",
  "email": "joao@exemplo.com",
  "senha": "senhaSegura123"
}
```

#### 2. Fazer Login
```http
POST http://localhost/api/index.php?param=auth/login
Content-Type: application/json

{
  "email": "joao@exemplo.com",
  "senha": "senhaSegura123"
}
```

**Copie o token da resposta.**

#### 3. Criar Produto (com Token)
```http
POST http://localhost/api/index.php?param=produto
Content-Type: application/json
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...

{
  "nome": "Mouse Gamer",
  "descricao": "Mouse RGB 16000 DPI",
  "preco": 150.00
}
```

#### 4. Listar Produtos (com Token)
```http
GET http://localhost/api/index.php?param=produto
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

#### 5. Criar Feedback (com Token)
```http
POST http://localhost/api/index.php?param=feedback
Content-Type: application/json
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...

{
  "produto_id": 1,
  "usuario_id": 1,
  "comentario": "Excelente mouse, muito preciso!",
  "nota": 5
}
```

### cURL (PowerShell)

**Login:**
```powershell
curl -X POST "http://localhost/api/index.php?param=auth/login" `
  -H "Content-Type: application/json" `
  -d '{"email":"joao@exemplo.com","senha":"senhaSegura123"}'
```

**Criar Produto:**
```powershell
$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."

curl -X POST "http://localhost/api/index.php?param=produto" `
  -H "Content-Type: application/json" `
  -H "Authorization: Bearer $token" `
  -d '{"nome":"Teclado Mecânico","descricao":"RGB, Switch Blue","preco":350.00}'
```

**Listar Produtos:**
```powershell
curl -X GET "http://localhost/api/index.php?param=produto" `
  -H "Authorization: Bearer $token"
```

**Deletar Produto (Query String):**
```powershell
curl -X DELETE "http://localhost/api/index.php?param=produto&id=3" `
  -H "Authorization: Bearer $token"
```

**Deletar Produto (Body JSON):**
```powershell
curl -X DELETE "http://localhost/api/index.php?param=produto" `
  -H "Content-Type: application/json" `
  -H "Authorization: Bearer $token" `
  -d '{"id":3}'
```

### JavaScript (Fetch API)

**Login e Armazenar Token:**
```javascript
async function login() {
  const response = await fetch('http://localhost/api/index.php?param=auth/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      email: 'joao@exemplo.com',
      senha: 'senhaSegura123'
    })
  });
  
  const data = await response.json();
  if (data.dados.token) {
    localStorage.setItem('token', data.dados.token);
    console.log('Token salvo:', data.dados.token);
  }
}
```

**Criar Feedback (com Token):**
```javascript
async function criarFeedback() {
  const token = localStorage.getItem('token');
  
  const response = await fetch('http://localhost/api/index.php?param=feedback', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    },
    body: JSON.stringify({
      produto_id: 1,
      usuario_id: 1,
      comentario: 'Produto incrível!',
      nota: 5
    })
  });
  
  const data = await response.json();
  console.log(data);
}
```

**Listar Feedbacks de um Produto:**
```javascript
async function listarFeedbacksProduto(produtoId) {
  const token = localStorage.getItem('token');
  
  const response = await fetch(
    `http://localhost/api/index.php?param=feedback/produto&produto_id=${produtoId}`,
    {
      headers: { 'Authorization': `Bearer ${token}` }
    }
  );
  
  const data = await response.json();
  console.log(data.dados);
}
```

---

## 📋 Padrões de Resposta

### Envelope Padrão
Todas as respostas seguem o formato:
```json
{
  "erro": null,
  "dados": { ... }
}
```

### Resposta de Sucesso (Listagem)
```json
{
  "erro": null,
  "dados": [
    {
      "id": 1,
      "nome": "Produto Exemplo",
      "descricao": "Descrição do produto",
      "preco": "100.00"
    }
  ]
}
```

### Resposta de Sucesso (Operação)
```json
{
  "erro": null,
  "dados": {
    "mensagem": "Dados Salvo com Sucesso!"
  }
}
```

### Resposta de Erro (Validação)
```json
{
  "erro": null,
  "dados": {
    "erro": "Campos obrigatórios: nome, email"
  }
}
```

### Resposta de Erro (Não Autorizado - 401)
```json
{
  "erro": null,
  "dados": {
    "erro": "Não autorizado"
  }
}
```

### Resposta de Erro (Parâmetro Ausente - 400)
```json
{
  "erro": null,
  "dados": {
    "erro": "Parâmetro obrigatório ausente: id"
  }
}
```

### Resposta de Erro (Rota Não Encontrada - 404)
```json
{
  "erro": null,
  "dados": {
    "erro": "Rota não encontrada"
  }
}
```

---

## 🔒 Códigos de Status HTTP

| Código | Descrição | Quando Ocorre |
|--------|-----------|---------------|
| 200 | OK | Requisição bem-sucedida |
| 400 | Bad Request | Parâmetro obrigatório ausente ou inválido |
| 401 | Unauthorized | Token JWT inválido ou ausente em rota protegida |
| 404 | Not Found | Rota não encontrada no mapeamento |
| 500 | Internal Server Error | Erro não tratado no servidor |

---

## 🧪 Testando a API

### Postman

1. **Configurar Ambiente**
   - Crie uma variável de ambiente `baseUrl`: `http://localhost/api/index.php`
   - Crie uma variável `token` (será preenchida automaticamente)

2. **Automatizar Token**
   - Na requisição de login, vá em **Tests** e adicione:
   ```javascript
   pm.environment.set("token", pm.response.json().dados.token);
   ```

3. **Usar Token Automaticamente**
   - Em rotas protegidas, aba **Authorization**
   - Type: **Bearer Token**
   - Token: `{{token}}`

4. **Exemplo de Requisição**
   ```
   GET {{baseUrl}}?param=produto
   Authorization: Bearer {{token}}
   ```

### Thunder Client (VS Code)

1. Instale a extensão **Thunder Client**
2. Crie um **Environment** com:
   ```json
   {
     "baseUrl": "http://localhost/api/index.php",
     "token": ""
   }
   ```
3. Nas requisições, use `{{baseUrl}}?param=rota`
4. Para autenticação: **Auth** > **Bearer** > `{{token}}`

### Insomnia

1. Crie uma pasta "Feedback API"
2. Configure uma **Base Environment**:
   ```json
   {
     "base_url": "http://localhost/api/index.php",
     "token": "_.token"
   }
   ```
3. Na requisição de login, configure **Response** > **Extract Value**:
   - Variable: `token`
   - JSONPath: `$.dados.token`
4. Use `{{ _.base_url }}?param=produto` e `Bearer {{ _.token }}`

---

## ⚙️ Detalhes Técnicos

### Roteamento Dinâmico

O sistema usa o parâmetro `?param=` para determinar a rota:
- `index.php` recebe `$_GET['param']`
- `Controller` passa para `Rotas::executar($rota)`
- `Rotas` mapeia a rota para um `Endpoint` (classe + método + flag autenticar)
- `Acao` usa **Reflection** para invocar o método e injetar parâmetros

### Injeção de Parâmetros

`Acao::getParam()` mescla:
1. `$_POST` (form-data, x-www-form-urlencoded)
2. `$_GET` (query string, exceto `param`)
3. `php://input` (JSON body)

A Reflection API injeta automaticamente por nome:
```php
// Controller
public function alterar($id, $nome, $preco) { ... }

// Requisição JSON
{ "id": 1, "nome": "Produto", "preco": 100 }

// Acao injeta automaticamente:
// $id = 1, $nome = "Produto", $preco = 100
```

### Autenticação JWT

- **Geração**: `JwtAuth::criarChave($payload)` no login
- **Validação**: `JwtAuth::verificar()` antes de executar rotas protegidas
- **Configuração**: Defina `$autenticar = true` no Endpoint
- **Secret Key**: Configurada em `JwtAuth` (altere em produção!)

### Pretty Print JSON

`Controller::verificarChamadas()` formata JSON com:
```php
json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
```

---

## 🛠️ Tecnologias Utilizadas

- **PHP 7.4+** - Linguagem de programação
- **MySQL 5.7+** - Banco de dados relacional
- **Apache** - Servidor web (XAMPP)
- **PDO** - PHP Data Objects para acesso seguro ao banco
- **Reflection API** - Injeção automática de parâmetros
- **JWT (firebase/php-jwt)** - Autenticação via JSON Web Tokens
- **Composer** - Gerenciador de dependências
- **JSON** - Formato de dados da API

---

## 📝 Boas Práticas Implementadas

✅ **Separação de responsabilidades** (MVC + Service + DAO)  
✅ **Injeção de dependências** via Factory Pattern  
✅ **Interfaces para abstração** (IProdutoDAO, IUsuarioDAO, IFeedbackDAO)  
✅ **Prepared Statements** com bind de parâmetros (proteção contra SQL Injection)  
✅ **Validação de entrada** em todos os controllers  
✅ **Respostas HTTP padronizadas** com envelope consistente  
✅ **Namespaces PSR-4** e autoload via Composer  
✅ **Singleton** para conexão PDO (evita múltiplas conexões)  
✅ **Autenticação JWT stateless** (escalável)  
✅ **Reflection API** para flexibilidade no roteamento  
✅ **Pretty Print JSON** com charset UTF-8  
✅ **Senha criptografada** com `password_hash()` (bcrypt)  
✅ **Validação de email** com `filter_var()`  
✅ **Mensagens de erro descritivas** para facilitar debugging  

---

## 🚨 Segurança

### Implementado

- ✅ Prepared Statements (previne SQL Injection)
- ✅ Password hashing com bcrypt
- ✅ Validação de entrada de dados
- ✅ JWT para autenticação stateless
- ✅ Rotas protegidas vs. públicas

### Recomendações para Produção

- 🔐 Usar HTTPS (SSL/TLS)
- 🔐 Alterar secret key do JWT
- 🔐 Implementar rate limiting
- 🔐 Adicionar CORS configurável
- 🔐 Logs de auditoria
- 🔐 Validação de tipos de arquivo (se houver upload)
- 🔐 Sanitização adicional de HTML (se renderizar conteúdo)

---

## 📚 Dependências

### Composer

```json
{
  "require": {
    "firebase/php-jwt": "^6.0"
  }
}
```

Instale com:
```bash
composer install
```

---

## 🔧 Troubleshooting

### Erro: "Namespace declaration statement has to be the very first statement"
- **Causa**: Espaço/BOM antes de `<?php`
- **Solução**: Remova qualquer espaço antes de `<?php` e salve em UTF-8 sem BOM

### Erro: "array_merge(): Argument #3 must be of type array, null given"
- **Causa**: JSON inválido no body (vírgula extra, aspas faltando)
- **Solução**: Valide o JSON ou use `x-www-form-urlencoded`

### Erro: "Unauthorized" em rota protegida
- **Causa**: Token JWT ausente ou inválido
- **Solução**: Faça login primeiro e copie o token para o header `Authorization: Bearer TOKEN`

### Produto não deleta/altera (200 OK sem efeito)
- **Causa**: DAO não retorna boolean baseado em `rowCount()`
- **Solução**: Verifique que `ProdutoDAO::deletar()` retorna `$stm->rowCount() > 0`

### "Class not found"
- **Causa**: Autoload não configurado ou namespace errado
- **Solução**: Execute `composer dump-autoload` e verifique namespaces

---

## 👨‍💻 Autor

**Matheus (TheuSoft)**
**Ana Clara (4N4CL4RA)**
Projeto acadêmico de API RESTful em PHP seguindo padrões MVC e boas práticas de desenvolvimento.

---
**Versão:** 2.0.0  
**Data:** Dezembro 2025  
**Repositório:** [TheuSoft/api](https://github.com/TheuSoft/api)

