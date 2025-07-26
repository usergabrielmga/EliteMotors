<?php

session_start();
require_once 'navbar.php'; 

if (!isset($_SESSION['usuario_id'])) {
    // Redireciona para o login se não estiver logado
    header("Location: login.php");
    exit;
}

$nomeUsuario = $_SESSION['usuario_nome'] ?? 'Usuário';

require_once('./connection/connect.php');

$stmt = $conn->query("SELECT * FROM marcas");
$marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$id = $_GET['id'] ?? '';
$carro = ['id_marca' => '', 'modelo' => '', 'ano' => '', 'preco' => ''];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM carros WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $carro = $stmt->fetch(PDO::FETCH_ASSOC);
}

$stmt = $conn->query("
    SELECT carros.*, marcas.nome AS marca_nome
    FROM carros
    JOIN marcas ON carros.id_marca = marcas.id
");
$carros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Carros</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para ícones -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .header {
            background-color: #343a40;
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
        }
        .btn i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
   

<div class="container py-4">
    <div class="header">
        <h2><i class="fa-solid"></i> Dashboard de Gestão</h2>
    </div>

    <div class="form-section mb-5">
        <h4 class="mb-4"><?= $id ? 'Editar' : 'Cadastrar' ?> Carro</h4>
        <form action="actions/<?= $id ? 'editCar.php' : 'insertCar.php' ?>" method="POST" enctype="multipart/form-data" class="row g-3">
            <?php if ($id): ?>
                <input type="hidden" name="id" value="<?= $carro['id'] ?>">
            <?php endif; ?>

            <div class="col-md-6">
                <label class="form-label">Marca</label>
                <select name="marca_id" class="form-select" required>
                    <option value="">Selecione uma marca</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?= $marca['id'] ?>" <?= ($carro['id_marca'] ?? '') == $marca['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($marca['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Modelo</label>
                <input type="text" name="modelo" class="form-control" value="<?= $carro['modelo'] ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Ano</label>
                <input type="number" name="ano" class="form-control" value="<?= $carro['ano'] ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Preço</label>
                <input type="number" step="0.01" name="preco" class="form-control" value="<?= $carro['preco'] ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Localização</label>
                <input type="text" name="localizacao" class="form-control" value="<?= $carro['localizacao'] ?? '' ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">KM Rodados</label>
                <input type="number" name="km_rodados" class="form-control" value="<?= $carro['km_rodados'] ?? '' ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Combustível</label>
                <input type="text" name="combustivel" class="form-control" value="<?= $carro['combustivel'] ?? '' ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Foto do carro</label>
                <input type="file" name="foto" class="form-control" <?= $id ? '' : 'required' ?>>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-floppy-disk"></i> <?= $id ? 'Atualizar' : 'Salvar' ?>
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <h4>Carros Cadastrados</h4>
        <div class="table-responsive mt-3">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Ano</th>
                        <th>Preço</th>
                        <th>KM Rodados</th>
                        <th>Localização</th>
                        <th>Combustível</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carros as $carro): ?>
                        <tr>
                            <td><?= $carro['id'] ?></td>
                            <td><?= $carro['marca_nome'] ?></td>
                            <td><?= $carro['modelo'] ?></td>
                            <td><?= $carro['ano'] ?></td>
                            <td>R$ <?= number_format($carro['preco'], 2, ',', '.') ?></td>
                            <td><?= $carro['km_rodados'] ?></td>
                            <td><?= $carro['localizacao'] ?></td>
                            <td><?= $carro['combustivel'] ?? 'N/A' ?></td>
                            <td>
                                <a href="?id=<?= $carro['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </a>
                                <a href="actions/delCar.php?id=<?= $carro['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i> Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($carros) === 0): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Nenhum carro cadastrado ainda.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle com Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
