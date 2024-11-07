<?php
include '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];
    $taxa_minima = 5.00;
    $preco_por_hora = 3.00;

    $sql = "SELECT placa, hora_entrada FROM entradas WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    $entrada = $stmt->fetch(PDO::FETCH_ASSOC);

    $hora_entrada = new DateTime($entrada["hora_entrada"]);
    $hora_saida = new DateTime();
    $intervalo = $hora_saida->diff($hora_entrada);

    $horas = ceil($intervalo->h + $intervalo->i / 60);
    $valor = $taxa_minima + ($preco_por_hora * $horas);

    $sql_update = "UPDATE entradas SET hora_saida = NOW(), valor = :valor WHERE id = :id";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->execute(['valor' => $valor, 'id' => $id]);

    $sql_insert_historico = "INSERT INTO historico (placa, hora_entrada, hora_saida, valor) 
                             VALUES (:placa, :hora_entrada, NOW(), :valor)";
    $stmt_historico = $conn->prepare($sql_insert_historico);
    $stmt_historico->execute([
        'placa' => $entrada["placa"],
        'hora_entrada' => $entrada["hora_entrada"],
        'valor' => $valor
    ]);

    echo "<script>var valorCobrado = $valor;</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/valor_saida.css">
    <title>Saída Registrada</title>
</head>
<body onload="showPopup(valorCobrado)">

<!-- Modal de Pop-up -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <p>Valor a ser cobrado: R$ <span id="valor"></span></p>
        <button onclick="confirmExit()">Confirmar</button>
    </div>
</div>

<script src="../scripts/valor_saida.js"></script>

</body>
</html>
