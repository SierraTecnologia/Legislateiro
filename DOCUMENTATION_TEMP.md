
---

## 📚 Introdução

### O que é o Legislateiro?

**Legislateiro** é uma biblioteca Laravel desenvolvida pela **SierraTecnologia/Rica Soluções** para gerenciamento de **contratos, legislação, termos e acordos legais** em projetos empresariais. O pacote fornece uma arquitetura robusta e extensível para criar, gerenciar e processar documentos legais e contratuais dentro do ecossistema Laravel.

### Objetivo e Motivação

O Legislateiro nasceu da necessidade de padronizar e centralizar o gerenciamento de:

- **Contratos comerciais** e acordos entre partes
- **Termos de uso** e políticas de privacidade
- **Legislação aplicável** a diferentes contextos de negócio
- **Documentação legal** com versionamento e rastreabilidade

### Benefícios

- ✅ **Arquitetura limpa**: Separação clara entre Models, Services, Repositories e Controllers
- ✅ **Integração Laravel**: Service Provider auto-descoberto, migrations, views e configurações
- ✅ **Extensibilidade**: Traits e interfaces para facilitar customizações
- ✅ **Padrões de qualidade**: PHPStan (nível 8), PHPCS (PSR-12), Psalm, PHPUnit
- ✅ **Ecossistema SierraTecnologia**: Integração nativa com outras libs (Crypto, Muleta, etc.)

### Inserção no Ecossistema SierraTecnologia/Rica Soluções

O Legislateiro faz parte de um conjunto de bibliotecas modulares que compõem a **plataforma Rica Soluções**:

- **Crypto**: Criptografia e segurança
- **Muleta**: Traits e helpers compartilhados
- **Transmissor**: Comunicação e notificações
- **Population**: Gestão de pessoas e entidades

Todas as libs seguem os mesmos padrões de arquitetura, testes e CI/CD, facilitando a manutenção e evolução.

---

## 📦 Instalação

### Requisitos Mínimos

- **PHP**: `^8.0`, `^8.1` ou `^8.2`
- **Laravel**: `^9.0` ou `^10.0`
- **Composer**: `^2.0`
- **Dependências**:
  - `sierratecnologia/crypto:dev-master`

### Instalação via Composer

```bash
composer require sierratecnologia/legislateiro
```

### Publicação de Configurações e Assets

Após a instalação, publique os arquivos de configuração, views e migrations:

```bash
# Publicar configurações
php artisan vendor:publish --provider="Legislateiro\LegislateiroProvider" --tag="config"

# Publicar views
php artisan vendor:publish --provider="Legislateiro\LegislateiroProvider" --tag="views"

# Publicar todas as configs do ecossistema SierraTecnologia
php artisan vendor:publish --tag="sitec-config"
```

### Executar Migrations

O Legislateiro inclui migrations para criar as tabelas necessárias:

```bash
php artisan migrate
```

### Registro de Service Providers

O Laravel 5.5+ registra automaticamente o `LegislateiroProvider` via auto-discovery. Caso necessário, adicione manualmente em `config/app.php`:

```php
'providers' => [
    // ...
    Legislateiro\LegislateiroProvider::class,
],
```

### Aliases (Facades)

A facade `Legislateiro` está disponível automaticamente:

```php
use Legislateiro\Facades\Legislateiro;

$version = Legislateiro::getVersion();
```

---

## 🏗️ Arquitetura e Estrutura Interna

### Principais Diretórios e Namespaces

```
src/
├── Cacheable/          # Traits para cache de dados
├── Console/            # Comandos Artisan
├── Contracts/          # Interfaces e contratos
├── Exceptions/         # Exceções customizadas
├── Facades/            # Facades Laravel
├── Http/
│   ├── Controllers/    # Controllers Admin e API
│   ├── Policies/       # Policies de autorização
│   └── Requests/       # Form Requests
├── Interfaces/         # Interfaces adicionais
├── Models/             # Eloquent Models
│   ├── ParteType.php   # Tipos de partes contratuais
│   ├── Term.php        # Termos legais
│   ├── TermStage.php   # Estágios de termos
│   ├── TermTemplate.php# Templates de termos
│   └── TermType.php    # Tipos de termos
├── Observers/          # Eloquent Observers
├── Repositories/       # Camada de acesso a dados
│   └── ContratoRepository.php
├── Resources/          # API Resources
├── Scopes/             # Query Scopes globais
├── Services/           # Lógica de negócio
│   ├── ContratoService.php
│   └── LegislateiroService.php
├── Traits/             # Traits reutilizáveis
│   ├── HasContracts.php
│   └── HasLegislacao.php
├── Legislateiro.php    # Classe principal
└── LegislateiroProvider.php # Service Provider
```

### Padrões Arquiteturais

O Legislateiro adota uma arquitetura em camadas inspirada em **Clean Architecture** e **DDD** (Domain-Driven Design):

1. **Models (Domain Layer)**: Entidades Eloquent com relacionamentos e scopes
2. **Repositories (Data Access Layer)**: Abstração de acesso ao banco de dados
3. **Services (Application Layer)**: Lógica de negócio e orquestração
4. **Controllers (Presentation Layer)**: Interface HTTP e validações
5. **Traits**: Comportamentos reutilizáveis (HasContracts, HasLegislacao)

### Como os Componentes Interagem

```
Request → Controller → Service → Repository → Model → Database
                ↓           ↓
             Policy    Observer
```

**Fluxo de exemplo**:

1. Usuário faz request para `ParteTypeController@index`
2. Controller valida via `LegislateiroRequest`
3. Controller chama `ContratoService@paginated()`
4. Service chama `ContratoRepository@paginated()`
5. Repository retorna dados paginados do Model
6. Service aplica regras de negócio (se necessário)
7. Controller retorna view ou JSON

### Convenções Internas da SierraTecnologia

- **Namespace raiz**: `Legislateiro\`
- **Service Provider**: Sempre sufixo `Provider` (ex: `LegislateiroProvider`)
- **Repositories**: Sempre sufixo `Repository` (ex: `ContratoRepository`)
- **Services**: Sempre sufixo `Service` (ex: `ContratoService`)
- **Traits**: Prefixo `Has` para traits de relacionamento (ex: `HasContracts`)
- **Configurações**: Publicadas em `config/legislateiro.php` e `config/sitec/`
- **Logs**: Canal dedicado `sitec-legislateiro` em `storage/logs/sitec-legislateiro.log`

---

## 🔍 Principais Classes e Responsabilidades

### 1. LegislateiroProvider (src/LegislateiroProvider.php)

**Responsabilidade**: Registrar serviços, configurações, rotas, views, migrations e commands.

**Principais métodos**:

- `boot()`: Registra diretórios, rotas e logger
- `register()`: Registra singletons e configurações
- `registerDirectories()`: Publica configs, views e translations
- `routes()`: Carrega rotas do pacote

**Exemplo de uso** (já executado automaticamente pelo Laravel):

```php
// O Laravel carrega automaticamente o provider
// Mas você pode acessar o singleton assim:
$legislateiro = app('legislateiro');
$version = $legislateiro->getVersion();
```

### 2. Legislateiro (src/Legislateiro.php)

**Responsabilidade**: Classe principal da lib, fornece métodos utilitários.

**Métodos principais**:

- `getVersion()`: Retorna a versão instalada do pacote

```php
use Legislateiro\Facades\Legislateiro;

$version = Legislateiro::getVersion(); // Ex: "1.0.0"
```

### 3. ContratoService (src/Services/ContratoService.php)

**Responsabilidade**: Lógica de negócio para contratos (CRUD, busca, cancelamento).

**Métodos principais**:

- `all()`: Retorna todos os contratos
- `paginated()`: Retorna contratos paginados
- `find($id)`: Busca contrato por ID
- `search($payload)`: Busca contratos por termo
- `create($payload)`: Cria novo contrato
- `update($id, $payload)`: Atualiza contrato
- `cancel($orderId)`: Cancela contrato e processa reembolso

**Exemplo de uso**:

```php
use Legislateiro\Services\ContratoService;

class MeuController extends Controller
{
    public function __construct(private ContratoService $contratoService)
    {
    }

    public function index()
    {
        $contratos = $this->contratoService->paginated();
        return view('contratos.index', compact('contratos'));
    }

    public function store(Request $request)
    {
        $contrato = $this->contratoService->create($request->validated());
        return redirect()->route('contratos.show', $contrato->id);
    }
}
```

### 4. ContratoRepository (src/Repositories/ContratoRepository.php)

**Responsabilidade**: Abstração de acesso aos dados de contratos.

**Métodos principais**:

- `all()`: Retorna todos os contratos
- `paginated()`: Retorna contratos paginados com ordenação
- `search($payload, $count)`: Busca com LIKE em todas as colunas
- `store($payload)`: Cria novo contrato
- `find($id)`: Busca contrato por ID
- `getByCustomer($id)`: Busca contratos de um cliente
- `update($order, $payload)`: Atualiza contrato

**Exemplo de uso**:

```php
use Legislateiro\Repositories\ContratoRepository;

class ContratoService
{
    public function __construct(private ContratoRepository $repo)
    {
    }

    public function buscarPorCliente(int $clienteId)
    {
        return $this->repo->getByCustomer($clienteId)->get();
    }
}
```

### 5. Traits: HasContracts e HasLegislacao

#### HasContracts (src/Traits/HasContracts.php)

**Responsabilidade**: Adiciona relacionamentos para entidades que possuem contratos.

**Métodos**:

- `phones()`: Relacionamento morphToMany com Phone
- `emails()`: Relacionamento morphToMany com Email
- `addresses()`: Relacionamento morphToMany com Address
- `sitios()`: Relacionamento morphToMany com Sitio

**Exemplo de uso**:

```php
use Legislateiro\Traits\HasContracts;

class Cliente extends Model
{
    use HasContracts;
}

// Agora o modelo Cliente tem:
$cliente->phones; // Collection de telefones
$cliente->emails; // Collection de e-mails
$cliente->addresses; // Collection de endereços
```

#### HasLegislacao (src/Traits/HasLegislacao.php)

**Responsabilidade**: Adiciona relacionamentos e métodos para entidades sujeitas à legislação.

```php
use Legislateiro\Traits\HasLegislacao;

class Contrato extends Model
{
    use HasLegislacao;
}

// Métodos disponíveis para vincular legislação ao contrato
```

### 6. Models

#### ParteType (src/Models/ParteType.php)

Representa tipos de partes contratuais (ex: "Contratante", "Contratado", "Testemunha").

#### Term (src/Models/Term.php)

Representa termos legais (ex: "Termo de Uso", "Política de Privacidade").

#### TermStage (src/Models/TermStage.php)

Representa estágios de evolução de um termo (ex: "Rascunho", "Em Revisão", "Publicado").

#### TermTemplate (src/Models/TermTemplate.php)

Templates pré-configurados para geração de termos.

#### TermType (src/Models/TermType.php)

Tipos de termos (ex: "Contratual", "Regulatório", "Política Interna").

---

## 🚀 Uso Prático

### Como Utilizar o Legislateiro em um Projeto Laravel

#### 1. Instalar e Configurar

```bash
composer require sierratecnologia/legislateiro
php artisan vendor:publish --provider="Legislateiro\LegislateiroProvider"
php artisan migrate
```

#### 2. Criar um Controller para Contratos

```php
<?php

namespace App\Http\Controllers;

use Legislateiro\Services\ContratoService;
use Legislateiro\Http\Requests\LegislateiroRequest;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    public function __construct(private ContratoService $contratoService)
    {
    }

    public function index()
    {
        $contratos = $this->contratoService->paginated();
        return view('contratos.index', compact('contratos'));
    }

    public function show($id)
    {
        $contrato = $this->contratoService->find($id);
        return view('contratos.show', compact('contrato'));
    }

    public function store(LegislateiroRequest $request)
    {
        $contrato = $this->contratoService->create($request->validated());
        return redirect()->route('contratos.show', $contrato->id)
            ->with('success', 'Contrato criado com sucesso!');
    }

    public function update($id, LegislateiroRequest $request)
    {
        $contrato = $this->contratoService->update($id, $request->validated());
        return redirect()->route('contratos.show', $contrato->id)
            ->with('success', 'Contrato atualizado com sucesso!');
    }

    public function cancel($id)
    {
        $resultado = $this->contratoService->cancel($id);

        if ($resultado) {
            return redirect()->route('contratos.index')
                ->with('success', 'Contrato cancelado e reembolso processado!');
        }

        return back()->with('error', 'Não foi possível cancelar o contrato.');
    }
}
```

#### 3. Criar Rotas

```php
// routes/web.php
use App\Http\Controllers\ContratoController;

Route::middleware(['auth'])->group(function () {
    Route::resource('contratos', ContratoController::class);
    Route::post('contratos/{id}/cancelar', [ContratoController::class, 'cancel'])
        ->name('contratos.cancel');
});
```

#### 4. Usar Traits em Models Personalizados

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Legislateiro\Traits\HasContracts;

class Empresa extends Model
{
    use HasContracts;

    // Agora Empresa tem relacionamentos com telefones, emails, endereços, etc.
}

// Uso:
$empresa = Empresa::find(1);
$telefones = $empresa->phones; // Collection de telefones
$emails = $empresa->emails; // Collection de e-mails
```

#### 5. Criar View para Listagem

```blade
{{-- resources/views/contratos/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Contratos</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Status</th>
                <th>Valor</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contratos as $contrato)
            <tr>
                <td>{{ $contrato->id }}</td>
                <td>{{ $contrato->user->name }}</td>
                <td>{{ $contrato->status }}</td>
                <td>R$ {{ number_format($contrato->total, 2, ',', '.') }}</td>
                <td>{{ $contrato->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('contratos.show', $contrato->id) }}" class="btn btn-sm btn-info">
                        Ver
                    </a>
                    <form action="{{ route('contratos.cancel', $contrato->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Deseja realmente cancelar?')">
                            Cancelar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $contratos->links() }}
</div>
@endsection
```

### Boas Práticas e Recomendações

1. **Sempre use Service Layer**: Não acesse Repositories diretamente dos Controllers
2. **Validação**: Use Form Requests para validar dados de entrada
3. **Transações**: Para operações complexas (ex: `cancel()`), use transactions
4. **Logs**: O Legislateiro cria logs em `storage/logs/sitec-legislateiro.log`
5. **Policies**: Implemente Policies Laravel para autorização de acesso
6. **Testes**: Escreva testes para suas implementações customizadas

---

## 🔗 Integração com o Ecossistema SierraTecnologia

### Como o Legislateiro se Conecta com Outras Libs

O Legislateiro integra-se nativamente com:

#### 1. **Crypto** (sierratecnologia/crypto)

Usado para geração de UUIDs e criptografia:

```php
use SierraTecnologia\Crypto\Services\Crypto;

$uuid = Crypto::uuid(); // Gera UUID seguro
```

#### 2. **Muleta** (sierratecnologia/muleta)

Fornece traits compartilhados como `ConsoleTools`:

```php
use Muleta\Traits\Providers\ConsoleTools;

class LegislateiroProvider extends ServiceProvider
{
    use ConsoleTools; // Facilita registro de comandos
}
```

#### 3. **Transmissor** (sierratecnologia/transmissor)

Para envio de notificações sobre contratos:

```php
// Exemplo de integração (se Transmissor estiver instalado)
$this->transmissor->notificarContratoAssinado($contrato);
```

#### 4. **Population** (sierratecnologia/population)

Para gerenciamento de pessoas/entidades vinculadas a contratos.

### Padrões de Versionamento, Testes e CI/CD

Todas as libs do ecossistema seguem:

- **Versionamento Semântico**: `MAJOR.MINOR.PATCH` (ex: `1.2.3`)
- **Git Flow**: Branches `main`, `develop`, `feature/*`, `hotfix/*`
- **Testes Automatizados**: PHPUnit com cobertura mínima de 70%
- **Code Quality**: PHPStan (level 8), PHPCS (PSR-12), Psalm (level 7)
- **CI/CD**: GitHub Actions com workflows para testes, análise estática e deploy

### Como Múltiplos Projetos Adotam a Biblioteca

A SierraTecnologia mantém um repositório **mono-repo** com todas as libs. Projetos podem:

1. **Instalar via Composer** (modo produção):
   ```bash
   composer require sierratecnologia/legislateiro
   ```

2. **Usar symlinks locais** (modo desenvolvimento):
   ```json
   {
       "repositories": [
           {"type": "path", "url": "../libs/*", "options": {"symlink": true}}
       ]
   }
   ```

3. **Atualizar em conjunto**: Todas as libs são atualizadas simultaneamente no mono-repo

---

## 🛠️ Extensão e Customização

### Como Estender o Legislateiro Sem Quebrar Compatibilidade

#### 1. Estender Models

```php
<?php

namespace App\Models;

use Legislateiro\Models\Term as BaseTerm;

class Term extends BaseTerm
{
    // Adicionar novos métodos ou sobrescrever comportamentos

    public function versoes()
    {
        return $this->hasMany(TermVersao::class);
    }

    public function publicar()
    {
        $this->status = 'publicado';
        $this->published_at = now();
        $this->save();

        // Lógica adicional de publicação
    }
}
```

#### 2. Estender Services

```php
<?php

namespace App\Services;

use Legislateiro\Services\ContratoService as BaseService;

class ContratoService extends BaseService
{
    public function criarComNotificacao($payload)
    {
        $contrato = $this->create($payload);

        // Adicionar notificação customizada
        $this->notificarPartes($contrato);

        return $contrato;
    }

    private function notificarPartes($contrato)
    {
        // Lógica de notificação
    }
}
```

#### 3. Criar Novos Repositories

```php
<?php

namespace App\Repositories;

use Legislateiro\Models\Term;

class TermRepository
{
    public function __construct(private Term $model)
    {
    }

    public function buscarPublicados()
    {
        return $this->model
            ->where('status', 'publicado')
            ->orderBy('published_at', 'desc')
            ->get();
    }
}
```

#### 4. Adicionar Novos Módulos

Crie um novo Service Provider que estende o Legislateiro:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LegislateiroExtensionProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->extend('legislateiro', function ($legislateiro, $app) {
            // Adicionar funcionalidades ao singleton
            return $legislateiro;
        });
    }

    public function boot()
    {
        // Carregar views customizadas
        $this->loadViewsFrom(__DIR__.'/../../resources/views/legislateiro', 'legislateiro-custom');
    }
}
```

#### 5. Criar Comandos Artisan Personalizados

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Legislateiro\Services\ContratoService;

class PublicarTermosCommand extends Command
{
    protected $signature = 'legislateiro:publicar-termos';
    protected $description = 'Publica termos pendentes';

    public function __construct(private ContratoService $contratoService)
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Publicando termos...');

        // Lógica de publicação

        $this->info('Termos publicados com sucesso!');
    }
}
```

### Como Adicionar Novos Traits

```php
<?php

namespace App\Traits;

trait HasAssinaturasEletronicas
{
    public function assinaturas()
    {
        return $this->morphMany(AssinaturaEletronica::class, 'assinavel');
    }

    public function assinar($signatario, $certificado)
    {
        return $this->assinaturas()->create([
            'signatario_id' => $signatario->id,
            'certificado_digital' => $certificado,
            'assinado_em' => now(),
        ]);
    }
}
```

### Boas Práticas para Manutenção e Evolução

1. **Nunca modifique arquivos do vendor**: Sempre estenda classes
2. **Use Dependency Injection**: Facilita testes e manutenção
3. **Documente suas extensões**: Mantenha README atualizado
4. **Siga PSR-12**: Mantenha consistência de código
5. **Escreva testes**: Para todas as novas funcionalidades
6. **Versionamento**: Use tags Git para releases
7. **Migrations**: Sempre crie migrations reversíveis (down)

---

## 💡 Exemplos Reais

### Caso de Uso 1: Sistema de Contratos Comerciais

**Contexto**: Empresa de software precisa gerenciar contratos com clientes B2B.

**Antes do Legislateiro**:
- Código duplicado em múltiplos projetos
- Falta de padronização nos contratos
- Dificuldade em rastrear versões de termos
- Retrabalho em integrações de pagamento

**Depois do Legislateiro**:
- Service Layer reutilizável (`ContratoService`)
- Models padronizados (`Term`, `TermTemplate`)
- Integração nativa com Crypto para segurança
- Fluxo de cancelamento com reembolso automatizado

**Código**:

```php
// Criar contrato comercial
$contrato = $contratoService->create([
    'user_id' => $cliente->id,
    'tipo' => 'comercial',
    'valor_mensal' => 1500.00,
    'duracao_meses' => 12,
    'termo_id' => $termoPadrao->id,
]);

// Listar contratos do cliente
$contratos = $contratoRepository->getByCustomer($cliente->id);

// Cancelar com reembolso proporcional
$contratoService->cancel($contrato->id);
```

**Benefícios**:
- ⏱️ Redução de 70% no tempo de desenvolvimento
- 🔒 Conformidade com LGPD (rastreabilidade)
- 📊 Relatórios de contratos em tempo real

---

### Caso de Uso 2: Portal de Termos de Uso Multi-Tenant

**Contexto**: SaaS multi-tenant precisa gerenciar termos de uso específicos por tenant.

**Implementação**:

```php
// Criar termo para tenant
$termo = Term::create([
    'tenant_id' => $tenant->id,
    'tipo' => 'termo_uso',
    'versao' => '1.0',
    'conteudo' => $conteudoHtml,
    'vigencia_inicio' => now(),
]);

// Usar trait HasLegislacao no modelo Tenant
class Tenant extends Model
{
    use HasLegislacao;
}

// Buscar termo vigente do tenant
$termoVigente = $tenant->termos()
    ->where('vigencia_inicio', '<=', now())
    ->whereNull('vigencia_fim')
    ->first();
```

**Benefícios**:
- 🏢 Isolamento de dados por tenant
- 📝 Versionamento automático de termos
- ✅ Aceite de termos rastreável por usuário

---

### Caso de Uso 3: Integração com Painel Administrativo

**Contexto**: Equipe de operações precisa gerenciar contratos via painel admin.

**Implementação** (usando rotas admin do Legislateiro):

```php
// O Legislateiro já fornece rotas admin:
// /admin/legislateiro/parteTypes (gerenciar tipos de partes)

// Estender controller admin
class CustomParteTypeController extends \Legislateiro\Http\Controllers\Admin\ParteTypeController
{
    public function index()
    {
        $tipos = ParteType::with('contratos')->paginate(25);
        return view('admin.legislateiro.parte-types.index', compact('tipos'));
    }
}
```

**Integração com AdminLTE** (já configurado no menu):

O Legislateiro já registra menu items no painel:

```php
// LegislateiroProvider.php linha 46-78
public static $menuItens = [
    [
        'text' => 'Legislateiro',
        'icon' => 'fas fa-fw fa-search',
        'section' => 'rica',
        'level' => 3, // Root only
    ],
    'Legislateiro' => [
        'Contratos' => [
            [
                'text' => 'Projetos',
                'route' => 'admin.legislateiro.parteTypes.index',
                'icon' => 'fas fa-fw fa-ship',
            ],
        ],
    ],
];
```

**Benefícios**:
- 🎨 Interface admin pronta para uso
- 🔐 Controle de acesso por nível de usuário
- 📊 Dashboards de contratos

---

## 🤝 Guia de Contribuição

### Como Contribuir para o Projeto

1. **Fork o repositório** no GitHub
2. **Clone seu fork**:
   ```bash
   git clone https://github.com/seu-usuario/Legislateiro.git
   cd Legislateiro
   ```

3. **Crie uma branch** para sua feature:
   ```bash
   git checkout -b feature/minha-nova-funcionalidade
   ```

4. **Instale as dependências**:
   ```bash
   composer install
   ```

5. **Faça suas alterações** seguindo os padrões

6. **Execute os testes**:
   ```bash
   vendor/bin/phpunit
   vendor/bin/phpstan analyse src/
   vendor/bin/phpcs --standard=PSR12 src/
   ```

7. **Commit suas mudanças**:
   ```bash
   git add .
   git commit -m "feat: adiciona suporte para assinaturas eletrônicas"
   ```

8. **Push para seu fork**:
   ```bash
   git push origin feature/minha-nova-funcionalidade
   ```

9. **Abra um Pull Request** no GitHub

### Convenções de Commit

Seguimos o padrão **Conventional Commits**:

```
<tipo>(<escopo>): <descrição curta>

<corpo opcional>

<rodapé opcional>
```

**Tipos**:
- `feat`: Nova funcionalidade
- `fix`: Correção de bug
- `docs`: Documentação
- `style`: Formatação (não afeta código)
- `refactor`: Refatoração
- `test`: Adicionar testes
- `chore`: Tarefas de manutenção

**Exemplos**:
```
feat(contratos): adiciona método para exportar PDF
fix(services): corrige cálculo de reembolso proporcional
docs(readme): atualiza seção de instalação
test(repositories): adiciona testes para ContratoRepository
```

### Nomenclatura de Branches

- `feature/nome-da-funcionalidade`: Novas funcionalidades
- `fix/nome-do-bug`: Correções de bugs
- `docs/nome-da-doc`: Melhorias na documentação
- `refactor/nome-da-refatoracao`: Refatorações
- `test/nome-do-teste`: Adição de testes

### Versionamento

Seguimos **Semantic Versioning** (SemVer):

- **MAJOR** (1.0.0): Mudanças incompatíveis com API anterior
- **MINOR** (0.1.0): Novas funcionalidades compatíveis
- **PATCH** (0.0.1): Correções de bugs

### Execução Local de Testes

#### PHPUnit (Testes Unitários)

```bash
vendor/bin/phpunit
```

#### PHPStan (Análise Estática - Nível 8)

```bash
vendor/bin/phpstan analyse src/ --level=8
```

#### PHPCS (Code Sniffer - PSR-12)

```bash
vendor/bin/phpcs --standard=PSR12 src/
```

#### Psalm (Análise de Tipos - Nível 7)

```bash
vendor/bin/psalm
```

#### GrumPHP (Executar todos os checks antes de commit)

```bash
vendor/bin/grumphp run
```

### Checklist de Qualidade

Antes de abrir um Pull Request, certifique-se de:

- [ ] Código segue PSR-12
- [ ] PHPStan passa sem erros (nível 8)
- [ ] Psalm passa sem erros (nível 7)
- [ ] Testes unitários cobrem novas funcionalidades
- [ ] Documentação atualizada (README, docblocks)
- [ ] Migrations são reversíveis (método `down()`)
- [ ] Nenhum dado sensível no código (senhas, tokens, etc.)
- [ ] Commits seguem Conventional Commits

---

## 📄 Licença e Contato

### Licença

Este software é licenciado sob **MIT License**. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

```
Copyright (c) 2008-2020 SierraTecnologia

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

### Contato da Equipe Técnica

- **Email de Suporte**: [help@sierratecnologia.com.br](mailto:help@sierratecnologia.com.br)
- **Issues no GitHub**: [https://github.com/sierratecnologia/legislateiro/issues](https://github.com/sierratecnologia/legislateiro/issues)
- **Slack da Comunidade**: [https://bit.ly/sierratecnologia-slack](https://bit.ly/sierratecnologia-slack)
- **Twitter**: [@sierratecnologia](https://twitter.com/sierratecnologia)
- **Website**: [https://sierratecnologia.com.br](https://sierratecnologia.com.br)

### Equipe

- **Ricardo Rebello Sierra** - Arquiteto Principal - [contato@ricardosierra.com.br](mailto:contato@ricardosierra.com.br)
- **SierraTecnologia Team** - Desenvolvimento e Manutenção

---

## 🔧 Ferramentas de Verificação GitHub

Este repositório utiliza **GitHub Actions** para garantir qualidade de código. Os seguintes workflows são executados automaticamente:

### Workflows Ativos

1. **Tests** (`.github/workflows/run-tests.yml`)
   - Executa testes em PHP 8.0, 8.1, 8.2
   - Testa com Laravel 9.x e 10.x
   - Badge: ![Tests](https://github.com/sierratecnologia/legislateiro/workflows/Tests/badge.svg)

2. **PHPStan** (`.github/workflows/phpstan.yml`)
   - Análise estática nível 8
   - Badge: ![PHPStan](https://github.com/sierratecnologia/legislateiro/workflows/PHPStan/badge.svg)

3. **PHPCS** (`.github/workflows/phpcs.yml`)
   - Verificação PSR-12
   - Badge: ![PHPCS](https://github.com/sierratecnologia/legislateiro/workflows/PHPCS/badge.svg)

4. **Psalm** (`.github/workflows/psalm.yml`)
   - Análise de tipos nível 7
   - Badge: ![Psalm](https://github.com/sierratecnologia/legislateiro/workflows/Psalm/badge.svg)

### Arquivos de Configuração

- **phpunit.xml**: Configuração de testes unitários
- **phpstan.neon**: PHPStan nível 8 com Laravel support
- **phpcs.xml**: Code Sniffer PSR-12 com regras customizadas
- **psalm.xml**: Psalm nível 7 com plugin Laravel
- **grumphp.yml**: Pre-commit hooks

### Como Executar Localmente

```bash
# Instalar dependências de desenvolvimento
composer install

# Executar todos os checks
composer test        # PHPUnit
composer stan        # PHPStan
composer cs-check    # PHPCS
composer psalm       # Psalm

# Corrigir automaticamente problemas de estilo
composer cs-fix
```

---

**Desenvolvido com ❤️ pela equipe SierraTecnologia/Rica Soluções**

