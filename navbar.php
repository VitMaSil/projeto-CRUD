<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Barra superior com saudação -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-1">
  <div class="container-fluid justify-content-end">
    <?php if (isset($_SESSION['usuario'])): ?>
      <span class="navbar-text text-light me-3">
        👋 Olá, <?= htmlspecialchars($_SESSION['usuario']) ?>
      </span>
    <?php endif; ?>
  </div>
</nav>

<!-- Barra principal com links -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">Meu CRUD</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavMain">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="home.php">Início</a></li>
        <li class="nav-item"><a class="nav-link" href="registros.php">Registros</a></li>

        <?php if (isset($_SESSION['usuario'])): ?>
          <?php if ($_SESSION['tipo'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link text-warning fw-bold" href="admin.php">Área Administrativa</a>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link text-danger fw-bold" href="logout.php">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
