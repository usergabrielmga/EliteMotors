<?php
require_once 'connection/connect.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Verifica se o e-mail já está cadastrado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $erro = "Este e-mail já está cadastrado.";
    } else {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        if ($stmt->execute([$nome, $email, $senhaHash])) {
            $mensagem = "Usuário cadastrado com sucesso!";
            header("Location: login.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar usuário.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link 
  rel="stylesheet" 
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>
<body class="d-flex flex-column justify-content-center align-items-center vh-100 bg-light">


  <!-- Botão voltar no canto -->
  <a href="index.html"
     class="position-absolute top-0 start-0 m-3 text-secondary d-flex align-items-center"
     style="font-size: 1rem; text-decoration: none;">
    <i class="bi bi-arrow-left me-1"></i>
    Voltar para a página principal
  </a>

  <!-- Seu formulário ou outro conteúdo aqui -->



    <form method="POST" action="" class="bg-dark p-4 rounded text-white shadow" style="width: 400px; height: auto;">
        <img class="position-relative mb-3" src="./imgs/profile.png" alt="Avatar" style="width: 100px; left: 50%; transform: translateX(-50%)">

        <h3 class="text-center mb-4">Cadastro de Usuário</h3>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>

        <?php if ($mensagem): ?>
            <div class="alert alert-success"><?= $mensagem ?></div>
        <?php endif; ?>

        <label>Nome:</label>
        <input type="text" name="nome" class="form-control border-0 shadow-none mb-3" required>

        <label>Email:</label>
        <input type="email" name="email" class="form-control border-0 shadow-none mb-3" required>

        <label>Senha:</label>
        <input type="password" name="senha" class="form-control border-0 shadow-none mb-4" required>

        <button class="btn btn-success w-100 mb-2" type="submit">Cadastrar</button>
        <a href="login.php" class="btn btn-link text-white text-center w-100">Já tem conta? Faça login</a>
    </form>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
