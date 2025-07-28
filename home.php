<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Bem-vindo ao Meu CRUD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<section class="p-5 bg-dark text-white text-center">
  <h1 class="display-4">Bem-vindo ao CRUD de Vitor Machado</h1>
  <p class="lead">Este sistema foi criado com o objetivo de praticar e melhorar habilidades em desenvolvimento web utilizando PHP, MySQLi e Bootstrap.</p>
  <a href="registros.php" class="btn btn-primary btn-lg mt-3">Ver Registros</a>
</section>

<section class="p-5">
  <div class="container">
    <h2>Sobre o Projeto</h2>
    <p>Este é um sistema simples de cadastro, com autenticação de usuários, controle de permissões e uma interface moderna.</p>
    <ul>
      <li>Login com validação</li>
      <li>Usuário comum e administrador</li>
      <li>Listagem e edição de dados</li>
    </ul>
  </div>
</section>

<section class="bg-light p-5">
  <div class="container text-center">
    <h3>Desenvolvido por Vitor Machado</h3>
    <p>Com muito café, foco e vontade de evoluir como desenvolvedor web.</p>
  </div>
</section>

</body>
</html>
