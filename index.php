<?php
// Configuração do banco de dados
$host = "localhost";      // Host do banco (normalmente localhost)
$usuario = "root";        // Usuário do banco
$senha = "";              // Senha do banco
$dbname = "crud";    // Nome do banco de dados

// Conexão com o banco de dados
$conn = new mysqli($host, $usuario, $senha, $dbname);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}

// Consulta SQL para buscar os dados da tabela "usuarios"
$sql = "SELECT id, nome, sobrenome FROM usuarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lista de Registros</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-5">
    <h2 class="mb-4">Lista de Registros</h2>

    <a href="inserir_nome.php" class="btn btn-success mb-3">
      <i class="bi bi-plus-lg"></i> Novo Registro
    </a>


    <!-- Verifica se a consulta retornou algum resultado -->
    <?php if ($result->num_rows > 0): ?>
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Sobrenome</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <!-- Laço para exibir os registros -->
          <?php
          while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['nome']; ?></td>
              <td><?php echo $row['sobrenome']; ?></td>
              <td>
                <a href="editar_nome.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning me-2">
                  <i class="bi bi-pencil-square"></i> Editar
                </a>
                <a href="excluir.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?');">
                  <i class="bi bi-trash"></i> Excluir
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>Nenhum registro encontrado.</p>
    <?php endif; ?>

    <!-- Fecha a conexão com o banco de dados -->
    <?php $conn->close(); ?>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>