# Sistema de gerenciamento de tarefas

Sistema oferece como serviço um gerenciamento de tarefas.

## Docker

- O sistema foi feito usando o Laravel 8 e o PHP 8 então é necessario a instalação dessas ferramentas para evitar imcompatibilidade. 

- O Banco de dados utilizado é o `postgresSQL`.

- Também é feito uso do `nginx` para servir o sistema. 

Para rodar o Laradock basta executar o comando abaixo. (Caso seja excetutado pela primeira vez downloads serão necessário )
```bash
docker compose up -d nginx postgres
```
```bash
docker exec -it todo-list-workspace-1 /bin/sh
```


## Documentação

Todo o sistema foi documentado usando a ferramenta `Scribe`.

Para a geração basta executar o comando abaixo.
```bash
php artisan scribe:generate
```

Para acessa-la depois da gera-la basta acessar.
```bash
http://{LINK}/docs
```

## Teste

Todas as funcionalidades podem ser testadas para verificar a integridade do sistema através do comando.
```bash
php artisan test
```
