<body>

<h1>📲 PicPay Simplificado - API de Transferências</h1>
<p>API RESTful para simular funcionalidades básicas do PicPay, permitindo transferências entre usuários com validações de regras de negócio e integrações externas.</p>

<h2>📋 Visão Geral</h2>

<h3>Funcionalidades Principais</h3>
<ul>
  <li>CRUD completo de usuários</li>
  <li>Transferências entre usuários comuns e lojistas</li>
  <li>Validações de saldo e tipo de usuário</li>
  <li>Integração com serviços externos de autorização e notificação</li>
  <li>Rollback automático em caso de falhas</li>
</ul>

<h3>Stack Tecnológica</h3>
<ul>
  <li>PHP 8.2 + Laravel 10</li>
  <li>MySQL 8.0</li>
  <li>Docker + Docker Compose</li>
  <li>RESTful API</li>
</ul>

<h2>🚀 Começando</h2>

<h3>Pré-requisitos</h3>
<ul>
  <li><a href="https://docs.docker.com/get-docker/">Docker</a></li>
  <li><a href="https://docs.docker.com/compose/install/">Docker Compose</a></li>
</ul>

<h3>Instalação</h3>
<pre><code>git clone https://github.com/seu-usuario/picpay-simplificado.git
cd picpay-simplificado

docker-compose up -d --build
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
</code></pre>

<h2>🌐 Endpoints da API</h2>

<h3>🔑 CRUD de Usuários</h3>

<h4>Criar novo usuário</h4>
<code>POST /api/v1/users</code>

<h4>Listar usuários</h4>
<code>GET /api/v1/users</code>

<pre><code>{
  "data": [
    {
      "id": 1,
      "name": "João Silva",
      "email": "joao@example.com",
      "type": "common",
      "balance": 1000.00
    }
  ]
}
</code></pre>

<h4>Gerenciar usuário específico</h4>
<code>GET | PUT | DELETE /api/v1/users/{id}</code>

<h4>Regras de Validação:</h4>
<ul>
  <li><code>email</code> e <code>cpf_cnpj</code> únicos</li>
  <li><code>type</code>: <code>common</code> ou <code>merchant</code></li>
  <li><code>balance</code> ≥ 0</li>
  <li><code>password</code> mínimo 6 caracteres</li>
</ul>

<h3>💸 Transferências</h3>

<h4>Realizar transferência</h4>
<code>POST /api/v1/transfer</code>

<pre><code>{
  "value": 100.50,
  "payer": 1,
  "payee": 2
}
</code></pre>

<h4>Regras de Negócio:</h4>
<ul>
  <li>Lojistas só podem receber</li>
  <li>Saldo suficiente no pagador</li>
  <li>Autorização externa obrigatória</li>
  <li>Notificação sempre enviada</li>
</ul>

<h2>📤 Respostas HTTP</h2>

<table>
  <thead>
    <tr>
      <th>Status</th>
      <th>Descrição</th>
      <th>Exemplo de Resposta</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>201</td><td>Recurso criado</td><td>{"id": 1, "name": "João Silva", ...}</td></tr>
    <tr><td>200</td><td>Sucesso</td><td>{"data": [...]}</td></tr>
    <tr><td>204</td><td>Conteúdo removido</td><td>-</td></tr>
    <tr><td>422</td><td>Erro de validação</td><td>{"message": "Saldo insuficiente"}</td></tr>
    <tr><td>403</td><td>Não autorizado</td><td>{"message": "Transação recusada"}</td></tr>
    <tr><td>404</td><td>Não encontrado</td><td>{"message": "Usuário não existe"}</td></tr>
    <tr><td>502</td><td>Serviço externo offline</td><td>{"message": "Serviço indisponível"}</td></tr>
  </tbody>
</table>

<h2>🔄 Fluxo Completo</h2>
<p>Passo a passo da transferência:</p>
<ol>
  <li>Criar usuários (payer e payee)</li>
  <li>Realizar transferência entre eles</li>
  <li>Consultar saldos atualizados</li>
</ol>

<h2>🧪 Testando a API</h2>

<h4>Criar Usuário</h4>
<pre><code>curl -X POST http://localhost:8000/api/v1/users \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Maria Souza",
    "email": "maria@example.com",
    "password": "senhasegura",
    "cpf_cnpj": "98765432100",
    "type": "merchant",
    "balance": 0.00
  }'</code></pre>

<h4>Realizar Transferência</h4>
<pre><code>curl -X POST http://localhost:8000/api/v1/transfer \
  -H "Content-Type: application/json" \
  -d '{"value": 50.75, "payer": 1, "payee": 2}'</code></pre>

<h4>Consultar Usuários</h4>
<pre><code>curl http://localhost:8000/api/v1/users</code></pre>

<h2>🐳 Comandos Docker Úteis</h2>

<table>
  <thead>
    <tr>
      <th>Comando</th>
      <th>Função</th>
    </tr>
  </thead>
  <tbody>
    <tr><td><code>docker-compose logs -f app</code></td><td>Monitorar logs da aplicação</td></tr>
    <tr><td><code>docker-compose exec app bash</code></td><td>Acessar terminal do container</td></tr>
    <tr><td><code>docker-compose down -v</code></td><td>Parar e remover containers e volumes</td></tr>
  </tbody>
</table>

<h2>🤝 Contribuição</h2>

<ol>
  <li>Faça um fork do projeto</li>
  <li>Crie sua branch: <code>git checkout -b feature/nova-funcionalidade</code></li>
  <li>Commit suas mudanças: <code>git commit -m 'Add nova funcionalidade'</code></li>
  <li>Push para sua branch: <code>git push origin feature/nova-funcionalidade</code></li>
  <li>Abra um Pull Request</li>
</ol>

<h2>📄 Licença</h2>
<p>Distribuído sob a <strong>MIT License</strong>. Veja o arquivo <code>LICENSE</code> para mais detalhes.</p>

</body>
</html>
