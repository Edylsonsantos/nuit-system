<?php
require_once 'src/Nuit.php';

$nuit = $_GET['nuit'];
$numeroDocumento = $_GET['numero_documento'];

$nuitObj = new Nuit();
$resultado = $nuitObj->pesquisarNuit($nuit, $numeroDocumento);

if ($resultado) {
    // Defina o cabeçalho para download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="dados_nuit.txt"');

    // Escreva os dados no arquivo
    echo "Nome: " . $resultado['nome_completo'] . "\n";
    echo "NUIT: " . $resultado['nuit'] . "\n";
    echo "Endereço: " . $resultado['endereco'] . "\n";
    echo "Data de Nascimento: " . $resultado['data_nascimento'] . "\n";
    echo "Tipo de Documento: " . $resultado['documento_tipo'] . "\n";
    echo "Número do Documento: " . $resultado['documento_numero'] . "\n";
    exit();
} else {
    echo "Nenhum dado encontrado.";
}
