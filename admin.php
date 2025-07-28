<?php
session_start();

// Segurança: só admins podem acessar
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include 'navbar.php';

// Conexão com banco
$conn = new mysqli('localhost', 'root', '', 'crud');
if ($conn->connect_error) {
    die('Erro: ' . $conn->connect_error);
}

// Atualizar tipo do usuário (promoção/rebaixamento)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['novo_tipo'])) {
    $id = intval($_POST['user_id']);
    $novo_tipo = $_POST['novo_tipo'] === 'admin' ? 'admin' : 'comum';

    $stmt = $conn->prepare("UPDATE usuarios_login SET tipo = ? WHERE id = ?");
    $stmt->bind_param("si", $novo_tipo, $id);
    $stmt->execute();
    $stmt->close();
}

// Excluir usuário (não pode excluir a si mesmo)
if (isset($_GET['excluir']) && is_numeric($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    if ($id !== $_SESSION['usuario_id']) {
        $conn->query("DELETE FROM usuarios_login WHERE id = $id");
    }
}

// Buscar todos os usuários
$usuarios = $conn->query("SELECT id, username, tipo FROM usuarios_login ORDER BY id");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Área Administrativa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4 text-center text-warning">Painel Administrativo</h2>

    <table class="table table-bordered">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Usuário</th>
          <th>Tipo</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($user = $usuarios->fetch_assoc()): ?>
          <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td>
              <form method="POST" class="d-flex align-items-center gap-2">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>" />
                <select name="novo_tipo" class="form-select form-select-sm" onchange="this.form.submit()">
                  <option value="comum" <?= $user['tipo'] === 'comum' ? 'selected' : '' ?>>Comum</option>
                  <option value="admin" <?= $user['tipo'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
              </form>
            </td>
            <td>
              <?php if ($user['id'] !== $_SESSION['usuario_id']): ?>
                <a href="?excluir=<?= $user['id'] ?>" onclick="return confirm('Excluir este usuário?')" class="btn btn-sm btn-danger">
                  Excluir
                </a>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <?php $conn->close(); ?>
  </div>
</body>
</html>
