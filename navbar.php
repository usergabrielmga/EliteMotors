<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$nomeUsuario = $_SESSION['usuario_nome'] ?? 'Usuário';
?>

<!-- Bootstrap Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">Minha Aplicação</a>
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav">
        <li class="nav-item me-3 d-flex align-items-center text-white">
          <i class="fa-solid fa-user me-1"></i> <?= htmlspecialchars($nomeUsuario) ?>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-light" href="logout.php">
            <i class="fa-solid fa-right-from-bracket"></i> Sair
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
