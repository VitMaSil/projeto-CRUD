<?php
// Configurações de conexão com o banco de dados
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'crud';

// Conexão
$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome'] ?? '');
    $sobrenome = trim($_POST['sobrenome'] ?? '');

    if (empty($nome) || empty($sobrenome)) {
        die("Nome e sobrenome são obrigatórios.");
    }

    // Prepara e executa INSERT com created_at = NOW()
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, sobrenome, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $nome, $sobrenome);

    if ($stmt->execute()) {
        ?>
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
          <meta charset="UTF-8" />
          <meta name="viewport" content="width=device-width, initial-scale=1" />
          <title>Sucesso</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
          <script>
            // Redirecionar após 5 segundos
            setTimeout(() => {
              window.location.href = 'index.php';
            }, 2000);
          </script>
        </head>
        <body>
          <div class="container mt-5 text-center">
            <h2>Dados inseridos com sucesso!</h2>
            <p>Redirecionando...</p>
            <div class="spinner-border text-success" role="status">
              <span class="visually-hidden">Carregando...</span>
            </div>
          </div>
        </body>
        </html>
        <?php
    } else {
        echo "Erro ao inserir usuário: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
