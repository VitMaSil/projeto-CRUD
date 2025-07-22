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

// Recebe e valida os dados do formulário
$id = intval($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$sobrenome = trim($_POST['sobrenome'] ?? '');

if ($id === 0 || empty($nome) || empty($sobrenome)) {
  die("Dados inválidos.");
}

// Prepara e executa o UPDATE com prepared statement
$stmt = $conn->prepare("UPDATE usuarios SET nome = ?, sobrenome = ? WHERE id = ?");
$stmt->bind_param("ssi", $nome, $sobrenome, $id);

if ($stmt->execute()) {
  // Redireciona automaticamente para index.php
  header("Location: index.php");
  exit(); // Sempre use exit após redirecionamento para garantir que o script pare aqui
} else {
  echo "Erro ao atualizar usuário: " . $stmt->error;
}


$stmt->close();
$conn->close();
