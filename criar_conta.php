<?php
session_start();

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';

    if (!$username || !$senha || !$confirma_senha) {
        $erro = 'Preencha todos os campos.';
    } elseif ($senha !== $confirma_senha) {
        $erro = 'As senhas não coincidem.';
    } else {
        // Conexão ao banco
        $conn = new mysqli('localhost', 'root', '', 'crud');
        if ($conn->connect_error) {
            die('Erro no banco: ' . $conn->connect_error);
        }

        // Verifica se usuário já existe
        $stmt = $conn->prepare("SELECT id FROM usuarios_login WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $erro = 'Usuário já existe.';
        } else {
            // Insere novo usuário com senha criptografada
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO usuarios_login (username, password) VALUES (?, ?)");
            $stmt->bind_param('ss', $username, $hash);

            if ($stmt->execute()) {
                $sucesso = 'Cadastro realizado com sucesso! Você será redirecionado para a página de Login!';
                header("refresh:4;url=login.php");
            } else {
                $erro = 'Erro ao cadastrar usuário: ' . $stmt->error;
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Criar Conta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Criar Nova Conta</h2>

    <?php if ($erro): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
    <?php elseif ($sucesso): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($sucesso); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="username" class="form-label">Usuário</label>
        <input type="text" name="username" id="username" class="form-control" required autofocus value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" />
      </div>
      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" name="senha" id="senha" class="form-control" required />
      </div>
      <div class="mb-3">
        <label for="confirma_senha" class="form-label">Confirme a Senha</label>
        <input type="password" name="confirma_senha" id="confirma_senha" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-success w-100">Cadastrar</button>
    </form>

    <div class="mt-3 text-center">
      <a href="home.php" class="btn btn-secondary">Voltar à tela inicial</a>
    </div>

    <p class="mt-3 text-center">
      Já tem conta? <a href="login.php">Faça login aqui</a>
    </p>
  </div>
</body>
</html>
