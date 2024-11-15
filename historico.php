<?php
include 'config/db_connect.php';

// Consulta para calcular a soma das entradas do dia atual
$sql_total_dia = "SELECT SUM(valor) AS total_dia FROM historico WHERE DATE(hora_saida) = CURDATE()";
$stmt_total_dia = $conn->prepare($sql_total_dia);
$stmt_total_dia->execute();
$result_total_dia = $stmt_total_dia->fetch(PDO::FETCH_ASSOC);
$total_dia = $result_total_dia['total_dia'] ?? 0;

// Consulta para calcular a soma das entradas do mês atual
$sql_total_mes = "SELECT SUM(valor) AS total_mes FROM historico WHERE MONTH(hora_saida) = MONTH(CURDATE()) AND YEAR(hora_saida) = YEAR(CURDATE())";
$stmt_total_mes = $conn->prepare($sql_total_mes);
$stmt_total_mes->execute();
$result_total_mes = $stmt_total_mes->fetch(PDO::FETCH_ASSOC);
$total_mes = $result_total_mes['total_mes'] ?? 0;

// Consulta para obter todos os registros do histórico, ordenados por hora de saída em ordem decrescente
$sql = "SELECT * FROM historico ORDER BY hora_saida DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Histórico de Estacionamento</title>
</head>
<body>
    <div class="button" style="width: 15%">
        <a href="index.php" class="hist-text">Inicio</a>
    </div>
    <h2 class="titulo">Histórico de Entradas e Saídas</h2>

    <!-- Exibir a soma das entradas do dia atual -->
    <p class="titulo">Total de Entradas do Dia: R$ <?php echo number_format($total_dia, 2, ',', '.'); ?></p>

    <!-- Exibir a soma das entradas do mês atual -->
    <p class="titulo">Total de Entradas do Mês: R$ <?php echo number_format($total_mes, 2, ',', '.'); ?></p>

    <table>
        <tr>
            <th>Placa</th>
            <th>Entrada</th>
            <th>Saída</th>
            <th>Valor</th>
        </tr>
        <?php foreach ($result as $row): ?>
            <tr>
                <td><?php echo $row['placa']; ?></td>
                <td><?php echo $row['hora_entrada']; ?></td>
                <td><?php echo $row['hora_saida']; ?></td>
                <td><?php echo 'R$ ' . number_format($row['valor'], 2, ',', '.'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
