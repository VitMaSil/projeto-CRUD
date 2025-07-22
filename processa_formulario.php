<?php
// Configurações de conexão com o banco de dados
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'crud';

// Conecta ao banco de dados
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $sobrenome = $_POST['sobrenome'] ?? '';

    // Proteção contra SQL Injection
    $nome = $conn->real_escape_string($nome);
    $sobrenome = $conn->real_escape_string($sobrenome);

    // Insere os dados na tabela
    $sql = "INSERT INTO usuarios (nome, sobrenome) VALUES ('$nome', '$sobrenome')";

    if ($conn->query($sql) === TRUE) {
        ?>
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="refresh" content="5;url=index.php">
            <title>Sucesso</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    background-color: #f8f9fa;
                }
                .success-box {
                    padding: 2rem;
                    border-radius: 1rem;
                    background: white;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                }
            </style>
        </head>
        <body>
            <div class="success-box text-center">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">✅ Dados inseridos com sucesso!</h4>
                    <p class="mb-3">Você será redirecionado em instantes.</p>
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Redirecionando...</span>
                    </div>
                    <p class="mt-2"><small>Redirecionando para a página inicial...</small></p>
                </div>
            </div>

            <script>
                setTimeout(function() {
                    window.location.href = "index.php";
                }, 3000);
            </script>
        </body>
        </html>
        <?php
    } else {
        echo "Erro: " . $conn->error;
    }
}

$conn->close();
?>
