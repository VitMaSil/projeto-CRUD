# Projeto CRUD
Este é um projeto CRUD desenvolvido com PHP, HTML, Bootstrap e MySQL.

## Pré-requisitos
- PHP 7.4 ou superior
- MySQL
- Servidor web (ex.: Laragon, XAMPP ou Apache)
- Composer (opcional, se usado no projeto)

## Instalação
1. Clone o repositório: `git clone <URL_DO_REPOSITORIO>`
2. Configure as credenciais do banco de dados:
   - Abra o arquivo de configuração do projeto (ex.: `config.php` ou similar) e insira as credenciais do seu banco de dados MySQL (ex.: host `localhost`, usuário `root`, senha vazia, nome do banco).
3. Importe o arquivo `database.sql` no MySQL para criar o banco de dados:
   - No HeidiSQL, clique em "Arquivo" > "Executar arquivo SQL" e selecione `database.sql`.
   - Ou, via terminal: `mysql -u root -p < database.sql`
4. Execute `composer install` (se o projeto usar Composer).
5. Inicie o servidor web (ex.: Laragon) e acesse o projeto no navegador.

## Como usar
- Acesse o projeto em `http://localhost/CRUD`.
- Siga as instruções na interface para criar, editar, visualizar ou excluir registros.