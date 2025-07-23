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

function redirectComErro($id, $msg) {
    $msg = urlencode($msg);
    header("Location: editar_nome.php?id=$id&erro=$msg");
    exit;
}

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'crud';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirectComErro(0, "Método inválido.");
}

$id = intval($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$sobrenome = trim($_POST['sobrenome'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email = trim($_POST['email'] ?? '');
$data_nascimento = $_POST['data_nascimento'] ?? null;
$cpf = trim($_POST['cpf'] ?? '');

if ($id <= 0 || $nome === '' || $sobrenome === '') {
  redirectComErro($id, "Dados inválidos. Preencha os campos corretamente.");
}

if ($cpf !== '' && !validarCPF($cpf)) {
  redirectComErro($id, "CPF inválido. Atualização cancelada.");
}

$sql = "UPDATE usuarios SET nome=?, sobrenome=?, telefone=?, email=?, data_nascimento=?, cpf=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $nome, $sobrenome, $telefone, $email, $data_nascimento, $cpf, $id);

if ($stmt->execute()) {
  header("Location: index.php?msg=Usuário atualizado com sucesso");
  exit;
} else {
  redirectComErro($id, "Erro ao atualizar usuário: " . $stmt->error);
}

$stmt->close();
$conn->close();
