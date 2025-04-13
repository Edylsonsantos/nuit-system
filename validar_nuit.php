<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validação de NUIT</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h1 {
            color: #2980B9;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        p {
            font-size: 16px;
            color: #34495E;
            text-align: center;
        }

        .btn-validar {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #2980B9;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            text-align: center;
        }

        .btn-validar:hover {
            background-color: #3498DB;
        }

        .btn-validar:active {
            background-color: #1A5276;
        }

        .success {
            color: #27AE60;
            font-weight: bold;
        }

        .error {
            color: #E74C3C;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Validação de NUIT</h1>

        <?php
        // Conectar ao banco de dados
        $servername = "localhost"; // Endereço do servidor do banco de dados
        $username = "root"; // Nome de usuário do banco de dados
        $password = ""; // Senha do banco de dados
        $dbname = "registro_nuit"; // Nome do banco de dados

        // Criar conexão
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar conexão
        if ($conn->connect_error) {
            die("<p class='error'>Falha na conexão: " . $conn->connect_error . "</p>");
        }

        // Verificar se o formulário foi enviado com o método POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obter o NUIT enviado pelo formulário
            if (isset($_POST['nuit'])) {
                $nuit = htmlspecialchars($_POST['nuit']);
                
                // Verificar se o NUIT existe no banco de dados
                $sql = "SELECT * FROM registro_nuit WHERE nuit = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $nuit);
                $stmt->execute();
                $result = $stmt->get_result();

                // Se encontrar o NUIT
                if ($result->num_rows > 0) {
                    // Atualizar a coluna 'verificado' para 1
                    $sql_update = "UPDATE registro_nuit SET verificado = 1 WHERE nuit = ?";
                    $stmt_update = $conn->prepare($sql_update);
                    $stmt_update->bind_param("s", $nuit);
                    
                    if ($stmt_update->execute()) {
                        echo "<p class='success'>NUIT verificado com sucesso.</p>";
                    } else {
                        echo "<p class='error'>Erro ao atualizar o status do NUIT.</p>";
                    }
                    
                    $stmt_update->close();
                } else {
                    echo "<p class='error'>NUIT inválido. Registro não encontrado.</p>";
                }

                // Fechar conexão
                $stmt->close();
            } else {
                echo "<p class='error'>Erro: NUIT não fornecido.</p>";
            }
        }

        // Fechar a conexão com o banco de dados
        $conn->close();
        ?>
        <a href="http://localhost/sportradar/registrar_nuit.php" class="btn-validar">Voltar</a>
    </div>
</body>
</html>
