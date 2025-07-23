<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
  header('Location: index.php'); // já logado, vai direto para CRUD
  exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $senha = $_POST['senha'] ?? '';

  if ($username && $senha) {
    // Conectar DB
    $conn = new mysqli('localhost', 'root', '', 'crud');
    if ($conn->connect_error) {
      die('Erro no banco: ' . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, password FROM usuarios_login WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();

      if (password_verify($senha, $user['password'])) {
        // Login OK, cria sessão
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['username'] = $username;

        header('Location: index.php');
        exit;
      } else {
        $erro = 'Senha incorreta.';
      }
    } else {
      $erro = 'Usuário não encontrado.';
    }

    $stmt->close();
    $conn->close();
  } else {
    $erro = 'Preencha usuário e senha.';
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Login</h2>

    <?php if ($erro): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="username" class="form-label">Usuário</label>
        <input type="text" name="username" id="username" class="form-control" required autofocus />
      </div>
      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" name="senha" id="senha" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
  </div>
</body>
</html>
