<?php
include 'config/db_connect.php';

// Recupera as taxas atuais para preencher os campos do modal
$sql_taxas = "SELECT taxa_minima, preco_por_hora FROM configuracoes WHERE id = 1";
$stmt_taxas = $conn->prepare($sql_taxas);
$stmt_taxas->execute();
$taxas = $stmt_taxas->fetch(PDO::FETCH_ASSOC);

$taxa_minima = $taxas['taxa_minima'];
$preco_por_hora = $taxas['preco_por_hora'];

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
    <h2 class="titulo">Entrada de Veículos</h2>
        <!-- Botão para abrir o modal de configurações -->
        <button onclick="showConfigModal()" class="button-modal" style="width: 15%">Configurações de Taxas</button>
    <div class="formulario">
        <form method="POST" action="" class="client-form">
            <div class="form-row" style="margin-bottom: 0px">
                <!-- <label>Placa:</label> -->
                <input type="text" name="placa" required class="form-input" placeholder="Placa">
                <button type="submit" class="button">Registrar Entrada</button>
                <div class="button">
                    <a href="historico.php" class="hist-text">Histórico</a>
                </div>
            </div>
        </form>
    </div>

    <h3 class="titulo">Veículos Estacionados</h3>
    <div class="carros">
        <?php foreach ($result as $row): ?>
            <div class="carro-card">
                <p class="normal-text">Placa </p>
                <div class="valores-card">
                    <p><?php echo $row['placa']; ?></p>
                </div>
                <p class="normal-text">Entrada </p>
                <div class="valores-card">
                    <p><?php echo date('d/m/Y H:i', strtotime($row['hora_entrada'])); ?></p>
                </div>
                <form action="actions/registrar_saida.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="button" style="margin-top:10px">Registrar Saída</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal de Configurações -->
    <div id="configModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeConfigModal()">&times;</span>
            <h3>Configurações de Taxas</h3>
            <form id="configForm" action="actions/update_taxas.php" method="POST">
                <label for="taxa_minima">Taxa Mínima:</label>
                <!-- Preenche o input com o valor atual da taxa mínima -->
                <input type="number" name="taxa_minima" id="taxa_minima" step="0.01" value="<?php echo htmlspecialchars($taxa_minima); ?>" required>
                
                <label for="preco_por_hora">Preço por Hora:</label>
                <!-- Preenche o input com o valor atual do preço por hora -->
                <input type="number" name="preco_por_hora" id="preco_por_hora" step="0.01" value="<?php echo htmlspecialchars($preco_por_hora); ?>" required>
                
                <button type="submit">Salvar</button>
            </form>
        </div>
    </div>

    <script src="scripts/config_taxas.js"></script>
</body>
</html>
