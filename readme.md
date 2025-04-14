## ✅ Explicações Técnicas do Desafio

### 🧠 Decisões Técnicas e Arquiteturais
O projeto foi estruturado com base em princípios de Domain-Driven Design (DDD) e Clean Architecture, buscando separar claramente as responsabilidades e facilitar a manutenção e evolução do sistema.

Domain: Camada central com as regras de negócio puras, entidades como Position, regras fiscais e DTOs.

Application: Contém os casos de uso (como CalculateTaxUseCase) responsáveis por orquestrar o fluxo entre as regras de negócio.

Infrastructure: Adapters e fábricas que lidam com a entrada de dados, integração com o terminal (STDIN) e criação dos componentes da aplicação.

Essa organização permite que a lógica de negócio seja facilmente testável e reutilizável, desacoplada de detalhes de entrada ou persistência.

O cálculo do imposto é feito através de um engine (TaxEngine) que aplica regras compostas. A gestão de posição é baseada em um padrão de estratégia, permitindo tratar diferentes tipos de operação de forma isolada e escalável.

___

### ⚙️ Justificativa para o uso de bibliotecas/frameworks
O projeto utiliza PHP puro, com algumas bibliotecas da comunidade para testes automatizados:

PHPUnit: framework principal para testes unitários e de integração no ecossistema PHP. Permite organização eficiente dos testes com uso de data providers, assertions e testes estruturados.

Mockery: biblioteca de mocking utilizada para criar dependências falsas (doubles) em testes unitários. Auxilia na simulação de comportamentos isolados, permitindo testes mais precisos e independentes.

Essas ferramentas foram escolhidas por serem amplamente utilizadas na comunidade PHP, com boa documentação e fácil integração com ferramentas de CI/CD.

___

### 🚀  Como executar o projeto
Suba o ambiente com Docker:

```bash
docker compose up -d
```

Instale as dependências:
```bash
docker compose exec app composer install
```

Execute com JSON

```bash
 echo '[{"operation": "buy", "unit-cost": 5000.00, "quantity": 10},{"operation": "sell", "unit-cost": 4000.00, "quantity": 5},{"operation": "buy", "unit-cost": 15000.00, "quantity": 5},{"operation": "buy", "unit-cost": 4000.00, "quantity": 2},{"operation": "buy", "unit-cost": 23000.00, "quantity": 2},{"operation": "sell", "unit-cost": 20000.00, "quantity": 1},{"operation": "sell", "unit-cost": 12000.00, "quantity": 10},{"operation": "sell", "unit-cost": 15000.00, "quantity": 3}]' | docker compose exec -T app php src/main.php
```

Execute com JSON em um arquivo
```bash
 docker compose exec -T app php src/main.php < tests/Fixtures/Integration/Main/input/multiple-simulations-are-isolated.txt
```

---
### 🧪 Como executar os testes
Os testes estão organizados em:

- tests/Unit: cobre regras e componentes isoladamente

- tests/Integration: realiza testes ponta-a-ponta utilizando fixtures

Execute os testes com:

```bash
 docker compose exec -T app php vendor/bin/phpunit
```