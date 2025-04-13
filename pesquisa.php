<?php
require_once 'src/Nuit.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuit = $_POST['nuit'];
    $numeroDocumento = $_POST['numero_documento'];

    $nuitObj = new Nuit();
    $resultado = $nuitObj->pesquisarNuit($nuit, $numeroDocumento);

    if ($resultado) {
        header('Location: resultado.php?nuit=' . $nuit . '&numero_documento=' . $numeroDocumento);
        exit();
    } else {
        $erro = "<p class='error'>Nuit ou número de documento incorretos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa de NUIT</title>
    <link rel="stylesheet" href="style.css"> <!-- Link para o CSS -->
</head>
<body>
    <div class="container">
        <h1>Pesquisar NUIT</h1>
        <?php if (isset($erro)): ?>
            <p style="color:red;"><?= $erro; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="nuit" placeholder="Digite o NUIT" required>
            <input type="text" name="numero_documento" placeholder="Digite o Número do Documento" required>
            <button type="submit">Pesquisar</button>
        </form>
    </div>
</body>
</html>
