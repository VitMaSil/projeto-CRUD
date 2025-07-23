<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'crud';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Erro de conexão: " . $conn->connect_error);
}

$id = $_POST['id'];
$nome = $_POST['nome'];
$sobrenome = $_POST['sobrenome'];
$data_nascimento = $_POST['data_nascimento'] ?? null;
$telefone = $_POST['telefone'] ?? null;
$email = $_POST['email'] ?? null;

$sql = "UPDATE usuarios SET nome=?, sobrenome=?, data_nascimento=?, telefone=?, email=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $nome, $sobrenome, $data_nascimento, $telefone, $email, $id);

if ($stmt->execute()) {
  header("Location: index.php");
  exit;
} else {
  echo "Erro ao atualizar usuário: " . $stmt->error;
}

$conn->close();
?>
