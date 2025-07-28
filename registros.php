<?php include 'navbar.php'; 

// Configuração do banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$dbname = "crud";

$conn = new mysqli($host, $usuario, $senha, $dbname);
if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}

// Parâmetros
$busca = $_GET['busca'] ?? '';
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

// Ordenação
$coluna = $_GET['ordenar'] ?? 'id';
$direcao = $_GET['direcao'] ?? 'ASC';
$colunas_permitidas = ['id', 'nome', 'sobrenome'];
if (!in_array($coluna, $colunas_permitidas)) {
  $coluna = 'id';
}
$direcao = strtoupper($direcao) === 'DESC' ? 'DESC' : 'ASC';
$direcao_oposta = $direcao === 'ASC' ? 'DESC' : 'ASC';

// Filtro de busca
$filtro = '';
if (!empty($busca)) {
  $busca_esc = $conn->real_escape_string($busca);
  $filtro = "WHERE nome LIKE '%$busca_esc%' OR sobrenome LIKE '%$busca_esc%'";
}

// Total de registros
$sql_total = "SELECT COUNT(*) AS total FROM usuarios $filtro";
$result_total = $conn->query($sql_total);
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = max(1, ceil($total_registros / $limite));

// Consulta com os campos extras
$sql = "SELECT id, nome, sobrenome, telefone, email, data_nascimento, cpf FROM usuarios $filtro ORDER BY $coluna $direcao LIMIT $limite OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lista de Registros</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Lista de Registros</h2>

  <a href="inserir_nome.php" class="btn btn-success mb-3">
    <i class="bi bi-plus-lg"></i> Novo Registro
  </a>

  <form method="GET" class="mb-3">
    <div class="input-group">
      <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou sobrenome" value="<?= htmlspecialchars($busca) ?>">
      <input type="hidden" name="pagina" value="1">
      <input type="hidden" name="ordenar" value="<?= $coluna ?>">
      <input type="hidden" name="direcao" value="<?= $direcao ?>">
      <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Buscar</button>
    </div>
  </form>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <?php
          function ordenarLink($campo, $label, $colunaAtual, $direcaoAtual, $busca, $pagina) {
            $icone = '';
            if ($colunaAtual === $campo) {
              $icone = $direcaoAtual === 'ASC' ? 'bi bi-arrow-up' : 'bi bi-arrow-down';
            }
            $novaDirecao = ($colunaAtual === $campo && $direcaoAtual === 'ASC') ? 'DESC' : 'ASC';
            $url = "?pagina=$pagina&busca=" . urlencode($busca) . "&ordenar=$campo&direcao=$novaDirecao";
            return "<a href=\"$url\" class=\"text-white text-decoration-none\">$label <i class=\"$icone\"></i></a>";
          }
          ?>
          <th><?php echo ordenarLink('id', 'ID', $coluna, $direcao, $busca, $pagina); ?></th>
          <th><?php echo ordenarLink('nome', 'Nome', $coluna, $direcao, $busca, $pagina); ?></th>
          <th><?php echo ordenarLink('sobrenome', 'Sobrenome', $coluna, $direcao, $busca, $pagina); ?></th>
          <th>Ações</th>
          <th>Mais Infos</th>


        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
  <tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['nome']; ?></td>
    <td><?php echo $row['sobrenome']; ?></td>
    <td>
      <a href="editar_nome.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning me-2" data-bs-toggle="tooltip" title="Editar este registro">
        <i class="bi bi-pencil-square"></i> Editar
      </a>
      <a href="excluir.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Excluir este registro" onclick="return confirm('Tem certeza que deseja excluir?');">
        <i class="bi bi-trash"></i> Excluir
      </a>
    </td>
    <td class="text-center">
      <button class="btn btn-sm btn-info toggle-details" data-bs-toggle="tooltip" title="Mais informações" data-target="#detalhes-<?php echo $row['id']; ?>">
        <i class="bi bi-chevron-down"></i>
      </button>
    </td>
  </tr>
  <tr class="table-secondary collapse" id="detalhes-<?php echo $row['id']; ?>">
    <td colspan="5">
      <strong>Telefone:</strong> <?php echo htmlspecialchars($row['telefone'] ?? '—'); ?><br>
      <strong>Email:</strong> <?php echo htmlspecialchars($row['email'] ?? '—'); ?><br>
      <strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($row['data_nascimento'] ?? '—'); ?><br>
      <strong>CPF:</strong> <?php echo htmlspecialchars($row['cpf'] ?? '—'); ?>
    </td>
  </tr>
<?php endwhile; ?>

      </tbody>
    </table>

    <nav>
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
          <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
            <a class="page-link" href="?pagina=<?= $i ?>&busca=<?= urlencode($busca) ?>&ordenar=<?= $coluna ?>&direcao=<?= $direcao ?>">
              <?= $i ?>
            </a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php else: ?>
    <div class="alert alert-warning text-center">Nenhum registro encontrado.</div>
  <?php endif; ?>

  <?php $conn->close(); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.querySelectorAll('.toggle-details').forEach(button => {
    button.addEventListener('click', () => {
      const targetId = button.getAttribute('data-target');
      const detailsRow = document.querySelector(targetId);
      if (detailsRow.classList.contains('show')) {
        detailsRow.classList.remove('show');
        button.querySelector('i').classList.replace('bi-chevron-up', 'bi-chevron-down');
      } else {
        detailsRow.classList.add('show');
        button.querySelector('i').classList.replace('bi-chevron-down', 'bi-chevron-up');
      }
    });
  });
</script>

</body>
</html>

?>