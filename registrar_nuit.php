<?php
require 'gmail/EmailSender.php';
// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar ao banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "registro_nuit";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Função para gerar NUIT único
    function gerarNUIT($conn) {
        do {
            $nuit = rand(100000000, 999999999); // Gerar NUIT de 9 dígitos
            $sql = "SELECT * FROM registro_nuit WHERE nuit = '$nuit'";
            $result = $conn->query($sql);
        } while ($result->num_rows > 0); // Gera novamente se já existir
        return $nuit;
    }

    // Obter dados do formulário e validar
    $tipo_registro = $_POST['tipo_registro'];
    $nome = strtoupper($_POST['nome']);
    $endereco = $_POST['endereco'];
    $data_nascimento = $_POST['data_nascimento'];
    $documento_tipo = $_POST['documento_tipo'];
    $documento_numero = $_POST['documento_numero'];
    $data_validade = $_POST['data_validade'];
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : null;

    // Verificar duplicidade de documento no banco de dados
    $sql_verificar = "SELECT * FROM registro_nuit WHERE documento_tipo = '$documento_tipo' AND documento_numero = '$documento_numero'";
    $result_verificar = $conn->query($sql_verificar);

    if ($result_verificar->num_rows > 0) {
        $mensagem_erro = "O número do documento já está registrado!";
    } elseif ($documento_tipo == 'BI' && !preg_match("/^\d{13}$/", $documento_numero)) {
        $mensagem_erro = "Número de BI inválido! Deve conter exatamente 13 dígitos.";
    } elseif ($documento_tipo == 'Cédula' && empty($documento_numero)) {
        $mensagem_erro = "O número da Cédula não pode estar vazio!";    
    } elseif ($documento_tipo == 'Cartão Eleitor' && !preg_match("/^\d{8}$/", $documento_numero)) {
        $mensagem_erro = "Número de Cartão de Eleitor inválido! Deve conter exatamente 8 dígitos.";
    } elseif (strtotime($data_validade) <= time()) {
        $mensagem_erro = "A data de validade deve ser uma data futura.";
    } else {
        // Gerar NUIT automaticamente
        $nuit = gerarNUIT($conn);

        // Dados específicos para Colectivo
        if ($tipo_registro === 'Colectivo') {
            $empresa = strtoupper($_POST['empresa']);
            $responsavel = $_POST['responsavel'];
            $cargo = isset($_POST['cargo']) ? $_POST['cargo'] : null;

            // Verificar se o nome da empresa já existe
            $sql_verificar_empresa = "SELECT * FROM registro_nuit WHERE empresa_nome = '$empresa'";
            $result_verificar_empresa = $conn->query($sql_verificar_empresa);

            if ($result_verificar_empresa->num_rows > 0) {
                $mensagem_erro = "O nome da empresa já está registrado!";
            } else {
                // Inserir no banco de dados
                $sql = "INSERT INTO registro_nuit (tipo_registro, nome_completo, nuit, endereco, data_nascimento, documento_tipo, documento_numero, data_validade, email, telefone, empresa_nome, responsavel_nome, cargo) 
                        VALUES ('$tipo_registro', '$nome', '$nuit', '$endereco', '$data_nascimento', '$documento_tipo', '$documento_numero', '$data_validade', '$email', '$telefone', '$empresa', '$responsavel', '$cargo')";
                
                if ($conn->query($sql) === TRUE) {
                    $mensagem_sucesso = "Registro feito com sucesso! Seu NUIT é: " . $nuit;
                    $emailSender = new EmailSender();
                    // Conteúdo do email
                    $subtitulo = "Confirmação de Criação de NUIT";
                    $subject = htmlspecialchars($subtitulo);
                    $userName = htmlspecialchars($nome); // Substitua pelo nome do usuário real
                    // Envio do email
                    $emailSender->sendEmail($email, $nome, $subject, $userName, $nuit, $endereco, $data_nascimento, $documento_tipo, $documento_numero);
                }
                 else {
                    $mensagem_erro = "Erro ao registrar: " . $conn->error;
                }
            }
        } else {
            // Inserir no banco de dados para Individual
            $sql = "INSERT INTO registro_nuit (tipo_registro, nome_completo, nuit, endereco, data_nascimento, documento_tipo, documento_numero, data_validade, email, telefone) 
                    VALUES ('$tipo_registro', '$nome', '$nuit', '$endereco', '$data_nascimento', '$documento_tipo', '$documento_numero', '$data_validade', '$email', '$telefone')";

            if ($conn->query($sql) === TRUE) {
                $mensagem_sucesso = "Registro feito com sucesso! Seu NUIT é: " . $nuit;
            } else {
                $mensagem_erro = "Erro ao registrar: " . $conn->error;
            }
        }

        // Fechar conexão
        $conn->close();
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de NUIT</title>
    <link rel="stylesheet" href="./styles.css"> <!-- Arquivo de CSS opcional -->
</head>
<body>
    <div class="container">
        <div class="img-header">
            <img src="./nuit.png" alt="NUIT">
        </div>
        
        <?php if (!empty($mensagem_sucesso)): ?>
            <div class="success">
                <?php echo $mensagem_sucesso; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($mensagem_erro)): ?>
            <div class="error">
                <?php echo $mensagem_erro; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="tipo_registro" class="tipo_registro">Tipo de Registro:</label><br>
            <div class="label">
                <label for="individual">Individual</label>
                <input type="radio" id="individual" name="tipo_registro" value="Individual" required>
            </div>
            <div class="label">
                <label for="colectivo">Colectivo</label>
                <input type="radio" id="colectivo" name="tipo_registro" value="Colectivo" required>
            </div>

            <!-- Dados comuns obrigatórios -->
            <label for="nome">Nome Completo:</label><br>
            <input type="text" id="nome" name="nome" required><br>

            <label for="data_nascimento">Data de Nascimento:</label><br>
            <input type="date" id="data_nascimento" name="data_nascimento" required><br>

            <label for="endereco">Endereço:</label><br>
            <input type="text" id="endereco" name="endereco" required><br>

            <!-- Seleção de Documento -->
            <label for="documento_tipo">Tipo de Documento:</label><br>
            <select id="documento_tipo" name="documento_tipo" required>
                <option value="BI">BI</option>
                <option value="Cédula">Cédula</option>
                <option value="Cartão Eleitor">Cartão de Eleitor</option>
            </select><br>

            <label for="documento_numero">Número do Documento:</label><br>
            <input type="text" id="documento_numero" name="documento_numero" required><br>

            <label for="data_validade">Data de Validade:</label><br>
            <input type="date" id="data_validade" name="data_validade" required><br>

            <!-- Campos opcionais -->
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>

            <label for="telefone">Telefone (Opcional):</label><br>
            <input type="tel" id="telefone" name="telefone" pattern="\d{9}" maxlength="9"><br>

            <!-- Campos específicos para Colectivo -->
            <div id="colectivo_campos" style="display:none;">
                <label for="empresa">Nome da Empresa:</label><br>
                <input type="text" id="empresa" name="empresa"><br>

                <label for="responsavel">Nome do Responsável:</label><br>
                <input type="text" id="responsavel" name="responsavel"><br>

                <label for="cargo">Cargo (Opcional):</label><br>
                <input type="text" id="cargo" name="cargo"><br>
            </div>

            <button type="submit">Registrar</button>
        </form> 
    </div>

    <script>
        // Mostrar campos do Colectivo ou Individual
        document.getElementById('colectivo').addEventListener('change', function () {
            document.getElementById('colectivo_campos').style.display = 'block';
        });

        document.getElementById('individual').addEventListener('change', function () {
            document.getElementById('colectivo_campos').style.display = 'none';
        });
    </script>
</body>
</html>
