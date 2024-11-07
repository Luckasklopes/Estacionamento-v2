<?php
include 'config/db_connect.php';

$sql = "SELECT * FROM historico";
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
    <h2>Histórico de Entradas e Saídas</h2>
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
