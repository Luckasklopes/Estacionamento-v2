<?php
include 'config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["placa"])) {
    $placa = $_POST["placa"];

    $sql_check = "SELECT COUNT(*) AS total FROM entradas WHERE hora_saida IS NULL";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute();
    $row_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($row_check['total'] < 6) {
        $sql = "INSERT INTO entradas (placa) VALUES (:placa)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['placa' => $placa]);
    } else {
        echo "<script>alert('Limite máximo de 6 carros atingido.');</script>";
    }
}

$sql = "SELECT * FROM entradas WHERE hora_saida IS NULL";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/config_taxas.css">
    <title>Sistema de Estacionamento</title>
</head>
<body>
    <h2>Entrada de Veículos</h2>
    <form method="POST" action="">
        <label>Placa:</label>
        <input type="text" name="placa" required>
        <button type="submit">Registrar Entrada</button>
    </form>

    <h3>Veículos Estacionados</h3>
    <div class="carros">
        <?php foreach ($result as $row): ?>
            <div class="carro-card">
                <p>Placa: <?php echo $row['placa']; ?></p>
                <p>Entrada: <?php echo $row['hora_entrada']; ?></p>
                <form action="actions/registrar_saida.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit">Registrar Saída</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>


    <!-- Botão para abrir o modal de configurações -->
    <button onclick="showConfigModal()">Configurações de Taxas</button>

    <!-- Modal de Configurações -->
    <div id="configModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeConfigModal()">&times;</span>
            <h3>Configurações de Taxas</h3>
            <form id="configForm" action="actions/update_taxas.php" method="POST">
                <label for="taxa_minima">Taxa Mínima:</label>
                <input type="number" name="taxa_minima" id="taxa_minima" step="0.01" required>
                
                <label for="preco_por_hora">Preço por Hora:</label>
                <input type="number" name="preco_por_hora" id="preco_por_hora" step="0.01" required>
                
                <button type="submit">Salvar</button>
            </form>
        </div>
    </div>

    <script src="scripts/config_taxas.js"></script>
</body>
</html>
