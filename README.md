(PT-BR) # Projeto CRUD
Este é um projeto CRUD desenvolvido com PHP, HTML, Bootstrap e MySQL.

## Pré-requisitos
- PHP 7.4 ou superior
- MySQL
- Servidor web (ex.: Laragon, XAMPP ou Apache)
- Composer (opcional, se usado no projeto)

## Instalação
1. Clone o repositório: `git clone <(https://github.com/VitMaSil/projeto-CRUD/edit/master/README.md)>`
2. Configure as credenciais do banco de dados:
   - Abra o arquivo de configuração do projeto (ex.: `config.php` ou similar) e insira as credenciais do seu banco de dados MySQL (ex.: host `localhost`, usuário `root`, senha vazia, nome do banco).
3. Importe o arquivo `database.sql` no MySQL para criar o banco de dados:
   - No HeidiSQL, clique em "Arquivo" > "Executar arquivo SQL" e selecione `database.sql`.
   - Ou, via terminal: `mysql -u root -p < database.sql`
4. Execute `composer install` (se o projeto usar Composer).
5. Inicie o servidor web (ex.: Laragon) e acesse o projeto no navegador.

## Como usar
- Acesse o projeto em `http://localhost/CRUD/index.php`.
- Siga as instruções na interface para criar, editar, visualizar ou excluir registros.

---------------------------------------------------------------------------------------------------------------------------------------------------------------

(US-EN) # CRUD Project

This is a CRUD (Create, Read, Update, Delete) project developed using PHP, HTML, Bootstrap, and MySQL.

## Prerequisites

- PHP 7.4 or higher  
- MySQL  
- Web server (e.g., Laragon, XAMPP, or Apache)  
- Composer (optional, if used in the project)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/VitMaSil/projeto-CRUD.git
Configure the database credentials:
Open the project's configuration file (e.g., config.php or similar) and enter your MySQL credentials (e.g., host: localhost, user: root, empty password, and your database name).

Import the database.sql file into MySQL to create the database:

Using HeidiSQL: go to File > Run SQL file and select database.sql

Or via terminal:

bash
Copiar
Editar
mysql -u root -p < database.sql
Run composer install (if the project uses Composer).

Start your web server (e.g., Laragon) and open the project in your browser.

How to Use
Access the project at:
http://localhost/CRUD/index.php

Use the interface to create, edit, view, or delete records.
