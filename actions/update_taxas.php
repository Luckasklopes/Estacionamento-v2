<?php
include '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taxa_minima = $_POST['taxa_minima'];
    $preco_por_hora = $_POST['preco_por_hora'];

    // Atualize as taxas no banco de dados (ajuste a consulta conforme a sua estrutura)
    $sql = "UPDATE configuracoes SET taxa_minima = :taxa_minima, preco_por_hora = :preco_por_hora WHERE id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['taxa_minima' => $taxa_minima, 'preco_por_hora' => $preco_por_hora]);

    echo "<script>alert('Taxas atualizadas com sucesso!'); window.location.href='../index.php';</script>";
}
?>
