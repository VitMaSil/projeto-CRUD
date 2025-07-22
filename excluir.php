<?php
// Configuração do banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$dbname = "crud";

// Conexão com o banco de dados
$conn = new mysqli($host, $usuario, $senha, $dbname);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o ID foi passado pela URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Garante que é um número inteiro

    // Prepara a consulta para deletar o usuário
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redireciona para a página principal após a exclusão
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao excluir registro: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID não fornecido.";
}

$conn->close();
?>
