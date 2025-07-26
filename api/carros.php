<?php

require_once('../connection/connect.php');

header('Content-Type: application/json');

$stmt = $conn->query("
    SELECT carros.*, marcas.nome AS marca_nome
    FROM carros
    JOIN marcas ON carros.id_marca = marcas.id
");
$carros = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($carros);
?>