<?php
require_once('../connection/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $id_marca = $_POST['marca_id'] ?? null;  // corrigido para o mesmo nome usado no insert
    $modelo = $_POST['modelo'] ?? null;
    $ano = $_POST['ano'] ?? null;
    $preco = $_POST['preco'] ?? null;

    if (!empty($id) && !empty($id_marca) && !empty($modelo) && !empty($ano) && !empty($preco)) {
        $stmt = $conn->prepare("UPDATE carros SET id_marca = :id_marca, modelo = :modelo, ano = :ano, preco = :preco WHERE id = :id");

        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':id_marca', $id_marca);
        $stmt->bindValue(':modelo', $modelo);
        $stmt->bindValue(':ano', $ano);
        $stmt->bindValue(':preco', $preco);

        if ($stmt->execute()) {
            header("Location: ../dashboard.php?sucesso=1");
            exit;
        } else {
            echo "Erro ao atualizar carro.";
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
} else {
    echo "Método inválido.";
}
