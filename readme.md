# Tax Calculator for Stock Operations

This project calculates capital gains tax on stock buy/sell operations, taking into account accumulated losses, exemption thresholds, and applicable rules from the Brazilian stock market.

___
### ⚠️ Notes

- This system does not handle cases where the sell operation exceeds the available position. It assumes that all sell operations are valid and the user has enough shares to sell.

- Case #9 from the official specification is incomplete
___
### Key Concepts

#### Operations:

Represented as JSON objects with the following fields:
- operation: "buy" or "sell"
- unit-cost: cost per share
- quantity: number of shares

Example:

```json
{ "operation": "buy", "unit-cost": 10.0, "quantity": 100 }
```

#### Tax Calculation:
The program calculates tax based on:
- Profit or loss per operation
- Accumulated losses from previous operations
- Exemption for sales totaling less than R$20,000
- A flat tax rate of 20% applied to taxable profit
___
### Components

#### Application:
Contains use cases such as CalculateTaxUseCase, which orchestrates the main tax calculation flow.

#### Domain:
Holds core business logic. Divided into subdomains:

- Positioning: updates position
- Taxation: defines tax rules and calculation logic
- Shared: reusable enums and DTOs

#### Infrastructure:
Adapters and factories:

- Reads input from STDIN
- Converts JSON into domain DTOs
- Creates and wires application services

___

### Install and Run

Start the containers

```bash
docker compose up -d
```

Install dependencies inside the container
```bash
docker compose exec app composer install
```

Run with inline JSON input

```bash
 echo '[{"operation": "buy", "unit-cost": 5000.00, "quantity": 10},{"operation": "sell", "unit-cost": 4000.00, "quantity": 5},{"operation": "buy", "unit-cost": 15000.00, "quantity": 5},{"operation": "buy", "unit-cost": 4000.00, "quantity": 2},{"operation": "buy", "unit-cost": 23000.00, "quantity": 2},{"operation": "sell", "unit-cost": 20000.00, "quantity": 1},{"operation": "sell", "unit-cost": 12000.00, "quantity": 10},{"operation": "sell", "unit-cost": 15000.00, "quantity": 3}]' | docker compose exec -T app php src/main.php
```

Run with input file
```bash
 docker compose exec -T app php src/main.php < tests/Fixtures/Integration/Main/input/multiple-simulations-are-isolated.txt
```

___

### Testing

Tests are organized as follows:

- tests/Unit: for isolated rules and components
- tests/Integration: end-to-end tests using fixtures

Run tests with:
```bash
 docker compose exec -T app php vendor/bin/phpunit
```