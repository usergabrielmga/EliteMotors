<?php
require_once('../connection/connect.php');

$foto = '';
if (!empty($_FILES['foto']['name'])) {
    $nomeTemporario = $_FILES['foto']['tmp_name'];
    $nomeOriginal = basename($_FILES['foto']['name']);
    $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

    // Validação simples da extensão
    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($extensao, $extensoesPermitidas)) {
        $nomeUnico = uniqid() . '.' . $extensao;
        $caminhoFinal = '../assets/img/' . $nomeUnico;

        if (move_uploaded_file($nomeTemporario, $caminhoFinal)) {
            $foto = 'assets/img/' . $nomeUnico;
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_marca = $_POST['marca_id'] ?? null; 
    $modelo = $_POST['modelo'] ?? null;
    $ano = $_POST['ano'] ?? null;
    $preco = $_POST['preco'] ?? null;
    $localizacao = $_POST['localizacao'];
    $km_rodados = $_POST['km_rodados'];


    if ($id_marca && $modelo && $ano && $preco) {
        $stmt = $conn->prepare("INSERT INTO carros (id_marca, modelo, ano, preco, localizacao, km_rodados, foto) VALUES (:id_marca, :modelo, :ano, :preco, :localizacao, :km_rodados, :foto)");

        $stmt->bindValue(':id_marca', $id_marca);
        $stmt->bindValue(':modelo', $modelo);
        $stmt->bindValue(':ano', $ano);
        $stmt->bindValue(':preco', $preco);
        $stmt->bindValue(':localizacao', $localizacao);
        $stmt->bindValue(':km_rodados', $km_rodados);
        $stmt->bindValue(':foto', $foto);

        if ($stmt->execute()) {
            header("Location: ../dashboard.php?sucesso=1");
            exit;
        } else {
            echo "Erro ao inserir carro.";
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
} else {
    echo "Método inválido.";
}


?>
