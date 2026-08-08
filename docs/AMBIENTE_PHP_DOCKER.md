# Imperial Pizza PHP FDD

Ambiente extraído do arquivo `imperial_controle_comandas_v4_integrado.html` sem alterar o HTML original. A aplicação nova usa PHP 8.3, HTML, CSS, Bootstrap 5, MySQL 8.4 e Docker.

## Arquitetura FDD

As funcionalidades foram separadas por domínio em `app/src`:

- `Team`: cadastro de pessoas e equipe do dia.
- `Operations`: abertura, encerramento da cozinha e finalização do dia.
- `Commands`: registro e fluxo de comandas entre cozinha, forno e despacho.
- `Mass`: área reservada para batidas e consumo de massas.
- `Dispatch`: atendimento/despacho via tabela `dispatches`.
- `Reports`: métricas de comandas, pizzas equivalentes e erros.

## Subir o ambiente

```bash
docker compose up --build
```

Acesse: <http://localhost:8080>

## Banco automático

O MySQL sobe automaticamente com os scripts da pasta `mysql-scripts` montados em `/docker-entrypoint-initdb.d`:

- `001_schema.sql`: tabelas da operação.
- `002_seed.sql`: configurações padrão e pessoas de exemplo.

## Observação sobre o README original

O `README.md` existente no repositório está codificado em UTF-16. Para evitar erro de visualização de arquivos binários no diff do PR, esta documentação nova fica neste arquivo separado em UTF-8, enquanto o `README.md` original permanece intacto.
