# SierraTecnologia Legislateiro

**SierraTecnologia Legislateiro** integration services legislateiro and providers for users required by various SierraTecnologia packages. Validator functionality, and basic controller included out-of-the-box.

[![Packagist](https://img.shields.io/packagist/v/sierratecnologia/legislateiro.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/sierratecnologia/legislateiro)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/sierratecnologia/legislateiro.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/sierratecnologia/legislateiro/)
[![Travis](https://img.shields.io/travis/sierratecnologia/legislateiro.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/sierratecnologia/legislateiro)
[![StyleCI](https://styleci.io/repos/60968880/shield)](https://styleci.io/repos/60968880)
[![License](https://img.shields.io/packagist/l/sierratecnologia/legislateiro.svg?label=License&style=flat-square)](https://github.com/sierratecnologia/legislateiro/blob/master/LICENSE)
[![Tests](https://github.com/sierratecnologia/legislateiro/workflows/Tests/badge.svg)](https://github.com/sierratecnologia/legislateiro/actions)
[![PHPStan](https://github.com/sierratecnologia/legislateiro/workflows/PHPStan/badge.svg)](https://github.com/sierratecnologia/legislateiro/actions)
[![PHPCS](https://github.com/sierratecnologia/legislateiro/workflows/PHPCS/badge.svg)](https://github.com/sierratecnologia/legislateiro/actions)
[![Psalm](https://github.com/sierratecnologia/legislateiro/workflows/Psalm/badge.svg)](https://github.com/sierratecnologia/legislateiro/actions)

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

### 1. LegislateiroProvider (src/LegislateiroProvider.php:23)

**Responsabilidade**: Registrar serviços, configurações, rotas, views, migrations e commands.

**Principais métodos**:

- `boot()`: Registra diretórios, rotas e logger
- `register()`: Registra singletons e configurações
- `registerDirectories()`: Publica configs, views e translations
- `routes()`: Carrega rotas do pacote

### 2. ContratoService (src/Services/ContratoService.php:10)

**Responsabilidade**: Lógica de negócio para contratos (CRUD, busca, cancelamento).

**Métodos principais**:

- `all()`: Retorna todos os contratos
- `paginated()`: Retorna contratos paginados
- `find($id)`: Busca contrato por ID
- `search($payload)`: Busca contratos por termo
- `create($payload)`: Cria novo contrato
- `update($id, $payload)`: Atualiza contrato
- `cancel($orderId)`: Cancela contrato e processa reembolso

Para exemplos completos de uso, consulte a seção **🚀 Uso Prático** abaixo.

---

## 🚀 Uso Prático

### Implementação Básica

```php
use Legislateiro\Services\ContratoService;

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
}
```

### Usando Traits em Models

```php
use Legislateiro\Traits\HasContracts;

class Empresa extends Model
{
    use HasContracts;
}

// Agora Empresa tem relacionamentos:
$empresa->phones;    // Telefones
$empresa->emails;    // E-mails
$empresa->addresses; // Endereços
```

---

## 🔗 Integração com o Ecossistema SierraTecnologia

O Legislateiro integra-se nativamente com:

- **Crypto** (sierratecnologia/crypto): Geração de UUIDs e criptografia
- **Muleta** (sierratecnologia/muleta): Traits compartilhados (ConsoleTools)
- **Transmissor**: Notificações sobre contratos
- **Population**: Gestão de pessoas/entidades vinculadas

### Padrões de Versionamento e CI/CD

Todas as libs do ecossistema seguem:

- **Versionamento Semântico**: `MAJOR.MINOR.PATCH`
- **Git Flow**: Branches `main`, `develop`, `feature/*`, `hotfix/*`
- **Testes Automatizados**: PHPUnit com cobertura mínima de 70%
- **Code Quality**: PHPStan (level 8), PHPCS (PSR-12), Psalm (level 7)
- **CI/CD**: GitHub Actions com workflows automatizados

---

## 🤝 Guia de Contribuição

### Como Contribuir

1. Fork o repositório no GitHub
2. Crie uma branch: `git checkout -b feature/minha-funcionalidade`
3. Instale as dependências: `composer install`
4. Faça suas alterações seguindo PSR-12
5. Execute os testes:
   ```bash
   vendor/bin/phpunit
   vendor/bin/phpstan analyse src/
   vendor/bin/phpcs --standard=PSR12 src/
   ```
6. Commit: `git commit -m "feat: adiciona nova funcionalidade"`
7. Push: `git push origin feature/minha-funcionalidade`
8. Abra um Pull Request

### Convenções de Commit

Seguimos **Conventional Commits**:

```
feat(contratos): adiciona método para exportar PDF
fix(services): corrige cálculo de reembolso proporcional
docs(readme): atualiza seção de instalação
```

### Execução Local de Testes

```bash
# PHPUnit
vendor/bin/phpunit

# PHPStan (nível 8)
vendor/bin/phpstan analyse src/ --level=8

# PHPCS (PSR-12)
vendor/bin/phpcs --standard=PSR12 src/

# Psalm (nível 7)
vendor/bin/psalm
```

---

## 🔧 Ferramentas de Verificação GitHub

Este repositório utiliza **GitHub Actions** para garantir qualidade de código:

### Workflows Ativos

- **Tests**: PHP 8.0, 8.1, 8.2 com Laravel 9.x e 10.x
- **PHPStan**: Análise estática nível 8
- **PHPCS**: Verificação PSR-12
- **Psalm**: Análise de tipos nível 7

### Arquivos de Configuração

- `phpunit.xml`: Configuração de testes
- `phpstan.neon`: PHPStan nível 8
- `phpcs.xml`: PSR-12 customizado
- `psalm.xml`: Psalm nível 7 com plugin Laravel

---

## 📚 Documentação Completa

Para documentação técnica detalhada, incluindo:

- Exemplos avançados de uso
- Casos de uso reais
- Extensão e customização
- Integração com outras libs
- Padrões arquiteturais

Consulte a [Documentação Técnica Completa](DOCUMENTATION_TEMP.md) no repositório.

---

## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.

---

## Support

The following support channels are available at your fingertips:

- [Chat on Slack](https://bit.ly/sierratecnologia-slack)
- [Help on Email](mailto:help@sierratecnologia.com.br)
- [Follow on Twitter](https://twitter.com/sierratecnologia)

---

## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to [help@sierratecnologia.com.br](help@sierratecnologia.com.br). All security vulnerabilities will be promptly addressed.


## About SierraTecnologia

SierraTecnologia is a software solutions startup, specialized in integrated enterprise solutions for SMEs established in Rio de Janeiro, Brazil since June 2008. We believe that our drive The Value, The Reach, and The Impact is what differentiates us and unleash the endless possibilities of our philosophy through the power of software. We like to call it Innovation At The Speed Of Life. That’s how we do our share of advancing humanity.


## License

This software is released under [The MIT License (MIT)](LICENSE).

(c) 2008-2020 SierraTecnologia, Some rights reserved.
