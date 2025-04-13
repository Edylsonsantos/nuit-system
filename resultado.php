<?php
require_once 'src/Nuit.php';
$nuit = $_GET['nuit'];
$numeroDocumento = $_GET['numero_documento'];

$nuitObj = new Nuit();
$resultado = $nuitObj->pesquisarNuit($nuit, $numeroDocumento);

// Verifica se os dados foram encontrados
if (!$resultado) {
    die("Nenhum dado encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Resultados NUIT</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div id="dados-nuit">
        <h1>Resultados da Pesquisa de NUIT</h1>
        <p id="nome"><strong>Nome:</strong> <?= htmlspecialchars($resultado['nome_completo']); ?></p>
        <p id="nuit"><strong>NUIT:</strong> <?= htmlspecialchars($resultado['nuit']); ?></p>
        <p id="endereco"><strong>Endereço:</strong> <?= htmlspecialchars($resultado['endereco']); ?></p>
        <p id="data-nascimento"><strong>Data de Nascimento:</strong> <?= htmlspecialchars($resultado['data_nascimento']); ?></p>
        <p id="tipo-documento"><strong>Tipo de Documento:</strong> <?= htmlspecialchars($resultado['documento_tipo']); ?></p>
        <p id="numero-documento"><strong>Número do Documento:</strong> <?= htmlspecialchars($resultado['documento_numero']); ?></p>
        <button id="baixar-pdf" class="btn">Baixar PDF</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
