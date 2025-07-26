<?php
require_once('../connection/connect.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM carros WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?excluido=$id");
        exit;
    } else {
        echo "Erro ao excluir o carro.";
    }
} else {
    echo "ID inválido.";
}
?>
