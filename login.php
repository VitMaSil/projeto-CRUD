<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (isset($_SESSION['usuario'])) {
  header('Location: home.php');
  exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $senha = $_POST['senha'] ?? '';

  if ($username && $senha) {
    $conn = new mysqli('localhost', 'root', '', 'crud');
    if ($conn->connect_error) {
      die('Erro no banco: ' . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, password, tipo FROM usuarios_login WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();

      if (password_verify($senha, $user['password'])) {
        $_SESSION['usuario'] = $username;
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['tipo'] = $user['tipo'];
        header('Location: home.php');
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5" style="max-width: 400px;">
    <div class="card shadow-lg">
      <div class="card-body">
        <h2 class="mb-4 text-center text-primary"><i class="bi bi-box-arrow-in-right"></i> Login</h2>

        <?php if ($erro): ?>
          <div class="alert alert-danger text-center"><?= htmlspecialchars($erro) ?></div>
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
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
          </button>
        </form>

        <hr class="my-4">

        <div class="d-grid gap-2">
          <a href="criar_conta.php" class="btn btn-success">
            <i class="bi bi-person-plus me-1"></i> Criar nova conta
          </a>
          <a href="home.php" class="btn btn-secondary">
            <i class="bi bi-house me-1"></i> Voltar à página inicial
          </a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
