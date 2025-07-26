<?php
session_start();
require_once 'connection/connect.php'; // Arquivo com conexão PDO
//senha: Teclado1@
// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['password'];

    // Consulta o usuário pelo e-mail
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Login OK: cria sessão e redireciona
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header("Location: dashboard.php");
        exit;
    } else {
        // Login inválido
        $erro = "E-mail ou senha incorretos!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link 
  rel="stylesheet" 
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <title>Login</title>
</head>
<body class="d-flex flex-column justify-content-center align-items-center vh-100">

    <body class="position-relative">

  <!-- Botão voltar no canto -->
  <a href="index.html"
     class="position-absolute top-0 start-0 m-3 text-secondary d-flex align-items-center"
     style="font-size: 1rem; text-decoration: none;">
    <i class="bi bi-arrow-left me-1"></i>
    Voltar para a página principal
  </a>

  <!-- Seu formulário ou outro conteúdo aqui -->
</body>


    <h2 class="d-flex justify-content-center">Acesse sua conta</h2>

    <?php if (!empty($erro)) : ?>
            <div class="erro alert alert-danger" style="width: 400px; text-direction: center;"><?= $erro ?></div>
        <?php endif; ?>

    <form class="bg-dark d-flex flex-column justify-content-end p-3 rounded " action="" method="POST" style="width: 400px; height: 400px">
        <img class="position-relative" src="./imgs/profile.png" alt="" style="width: 100px; left: 50%; transform: translateX(-50%)">
        <label class="d-block text-white" for="">Email:</label>
        <input class="form-control border-0 shadow-none outline-0 mb-2 ps-1" type="text" name="email" placeholder="Digite seu email de acesso:">

        <label class="d-block text-white" class="text-white" for="">Senha:</label>
        <input class="form-control border-0 shadow-none outline-0 mb-4 ps-1" type="password" name="password" placeholder="Digite sua senha">

        <button class="btn btn-success d-block mb-3 rounded" type="submit" style="height: 40px;">Entrar</button>
        <a href="register.php" class="btn btn-outline-secondary w-100">Cadastrar novo usuário</a>
    </form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>