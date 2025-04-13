<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registro_nuit";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Função para gerar NUIT único
function gerarNUIT($conn) {
    do {
        $nuit = rand(100000000, 999999999); // Gerar NUIT de 9 dígitos
        $sql = "SELECT * FROM nuit_registros WHERE nuit = '$nuit'";
        $result = $conn->query($sql);
    } while ($result->num_rows > 0); // Gera novamente se já existir

    return $nuit;
}

// Obter dados do formulário
$tipo_registro = $_POST['tipo_registro'];
$nome = $_POST['nome'];
$endereco = $_POST['endereco'];
$email = isset($_POST['email']) ? $_POST['email'] : null;
$telefone = isset($_POST['telefone']) ? $_POST['telefone'] : null;

// Gerar NUIT automaticamente
$nuit = gerarNUIT($conn);

// Dados específicos para Colectivo
if ($tipo_registro === 'Colectivo') {
    $empresa = $_POST['empresa'];
    $responsavel = $_POST['responsavel'];
    $cargo = isset($_POST['cargo']) ? $_POST['cargo'] : null;

    // Inserir no banco de dados
    $sql = "INSERT INTO nuit_registros (tipo_registro, nome, nuit, endereco, email, telefone, empresa, responsavel, cargo) 
            VALUES ('$tipo_registro', '$nome', '$nuit', '$endereco', '$email', '$telefone', '$empresa', '$responsavel', '$cargo')";
} else {
    // Inserir no banco de dados para Individual
    $sql = "INSERT INTO nuit_registros (tipo_registro, nome, nuit, endereco, email, telefone) 
            VALUES ('$tipo_registro', '$nome', '$nuit', '$endereco', '$email', '$telefone')";
}

// Executar query e verificar sucesso
if ($conn->query($sql) === TRUE) {
    echo "Registro feito com sucesso!<br>";
    echo "Seu NUIT é: " . $nuit;
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

// Fechar conexão
$conn->close();
?>
