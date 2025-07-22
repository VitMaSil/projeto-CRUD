<?php
// Conexão com MySQL usando MySQLi
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'crud';

$conn = new mysqli($host, $user, $pass, $db);

// Verifica conexão
if ($conn->connect_error) {
  die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o ID foi passado
$id = $_GET['id'] ?? null;
if (!$id) {
  die("ID do usuário não informado.");
}

// Prepara a consulta e busca os dados
$id = intval($id); // segurança básica
$sql = "SELECT * FROM usuarios WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
  die("Usuário não encontrado.");
}

$usuario = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar Usuário</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">Editar Usuário</h2>

    <form action="atualiza_usuario.php" method="POST">
      <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">

      <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
      </div>

      <div class="mb-3">
        <label for="sobrenome" class="form-label">Sobrenome</label>
        <input type="text" class="form-control" name="sobrenome" id="sobrenome" value="<?= htmlspecialchars($usuario['sobrenome']) ?>" required>
      </div>

      <button type="submit" class="btn btn-success">Salvar Alterações</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
