<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  header('Location: login.php');
  exit;
}

function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11) return false;
    if (preg_match('/(\d)\1{10}/', $cpf)) return false;
    for ($t = 9; $t < 11; $t++) {
        $sum = 0;
        for ($c = 0; $c < $t; $c++) {
            $sum += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $sum) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }
    return true;
}

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'crud';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Falha na conexão: " . $conn->connect_error);
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = trim($_POST['nome'] ?? '');
  $sobrenome = trim($_POST['sobrenome'] ?? '');
  $telefone = trim($_POST['telefone'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $data_nascimento = $_POST['data_nascimento'] ?? null;
  $cpf = trim($_POST['cpf'] ?? '');

  if ($nome === '' || $sobrenome === '') {
    $erro = "Nome e Sobrenome são obrigatórios.";
  } elseif ($cpf !== '' && !validarCPF($cpf)) {
    $erro = "CPF inválido. Por favor, insira um CPF válido.";
  } else {
    $sql = "INSERT INTO usuarios (nome, sobrenome, telefone, email, data_nascimento, cpf) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nome, $sobrenome, $telefone, $email, $data_nascimento, $cpf);

    if ($stmt->execute()) {
      $sucesso = "Usuário inserido com sucesso!";
      $_POST = [];
    } else {
      $erro = "Erro ao inserir usuário: " . $stmt->error;
    }
    $stmt->close();
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inserir Usuário</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">Inserir Novo Usuário</h2>

    <?php if ($erro): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php elseif ($sucesso): ?>
      <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <form id="formUsuario" action="" method="POST" novalidate>
      <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="sobrenome" class="form-label">Sobrenome</label>
        <input type="text" class="form-control" name="sobrenome" id="sobrenome" required value="<?= htmlspecialchars($_POST['sobrenome'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" class="form-control" name="telefone" id="telefone" maxlength="20" placeholder="(00) 00000-0000" value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="email@exemplo.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="data_nascimento" class="form-label">Data de Nascimento</label>
        <input type="date" class="form-control" name="data_nascimento" id="data_nascimento" value="<?= htmlspecialchars($_POST['data_nascimento'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" class="form-control" name="cpf" id="cpf" maxlength="14" placeholder="000.000.000-00" value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>" />
      </div>

      <button type="submit" class="btn btn-success">Inserir Usuário</button>
      <a href="index.php" class="btn btn-secondary ms-2">Voltar</a>
    </form>
  </div>

  <script>
    // Máscara CPF
    document.getElementById('cpf').addEventListener('input', function(e) {
      let v = e.target.value.replace(/\D/g, '');
      if (v.length > 11) v = v.slice(0, 11);
      v = v.replace(/(\d{3})(\d)/, '$1.$2');
      v = v.replace(/(\d{3})(\d)/, '$1.$2');
      v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
      e.target.value = v;
    });

    // Máscara Telefone
    document.getElementById('telefone').addEventListener('input', function(e) {
      let v = e.target.value.replace(/\D/g, '');
      if (v.length > 11) v = v.slice(0, 11);
      v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
      v = v.replace(/(\d{5})(\d)/, '$1-$2');
      e.target.value = v;
    });

    // Validação CPF antes do envio
    function validarCPF(cpf) {
      cpf = cpf.replace(/[^\d]+/g,'');    
      if(cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;

      let soma = 0;
      let resto;

      for (let i=1; i<=9; i++) {
        soma += parseInt(cpf.substring(i-1, i)) * (11 - i);
      }
      resto = (soma * 10) % 11;
      if ((resto === 10) || (resto === 11)) resto = 0;
      if (resto !== parseInt(cpf.substring(9, 10))) return false;

      soma = 0;
      for (let i=1; i<=10; i++) {
        soma += parseInt(cpf.substring(i-1, i)) * (12 - i);
      }
      resto = (soma * 10) % 11;
      if ((resto === 10) || (resto === 11)) resto = 0;
      if (resto !== parseInt(cpf.substring(10, 11))) return false;

      return true;
    }

    document.getElementById('formUsuario').addEventListener('submit', function(e) {
      const cpfInput = document.getElementById('cpf').value;
      if (cpfInput.trim() !== '' && !validarCPF(cpfInput)) {
        e.preventDefault();
        alert('CPF inválido. Por favor, corrija antes de enviar.');
        document.getElementById('cpf').focus();
      }
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
