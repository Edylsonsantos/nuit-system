<?php
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        // Configurações do servidor
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'edylsondossantos02@gmail.com'; // Seu endereço de email
        $this->mail->Password = 'cxxa cydg erko sfzr'; // Sua senha ou senha de aplicativo
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;

        $this->mail->CharSet = 'UTF-8';
    }

    public function sendEmail($to, $toName, $subject, $userName, $nuit, $endereco, $dataNascimento, $tipoDocumento, $numeroDocumento) {
        try {
            // Remetente e destinatário
            $this->mail->setFrom('suporte@nuit.gov.mz', 'Autoridade Tributaria de Mocambique');
            $this->mail->addAddress($to, $toName);
    
            // Conteúdo do email
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
    
            // Corpo do email com os dados do usuário
            $body = '
                <!DOCTYPE html>
                <html lang="pt">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Confirmação de Criação de NUIT</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 20px;
                        }
    
                        .container {
                            background-color: white;
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 20px;
                            border-radius: 10px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
    
                        h1 {
                            color: #2C3E50;
                            text-align: center;
                        }
    
                        p {
                            font-size: 16px;
                            color: #34495E;
                        }
    
                        .dados {
                            margin-bottom: 20px;
                        }
    
                        .dados span {
                            font-weight: bold;
                        }
    
                        .btn-validar {
                            display: block;
                            width: 100%;
                            padding: 10px;
                            background-color: #2980B9;
                            color: white;
                            border: none;
                            border-radius: 5px;
                            cursor: pointer;
                            font-size: 16px;
                            text-align: center;
                            margin-top: 10px;
                        }
    
                        .btn-validar:hover {
                            background-color: #3498DB;
                        }
    
                        .btn-validar:active {
                            background-color: #1A5276;
                        }
                    </style>
                </head>
                <body>
    
                    <div class="container">
                        <h1>Criação de NUIT Concluída</h1>
                        
                        <div class="dados">
                            <p><span>Nome Completo:</span> <strong>' . htmlspecialchars($userName) . '</strong></p>
                            <p><span>NUIT criado:</span> <strong>' . htmlspecialchars($nuit) . '</strong></p>
                            <p><span>Endereço:</span> <strong>' . htmlspecialchars($endereco) . '</strong></p>
                            <p><span>Data de Nascimento:</span> <strong>' . htmlspecialchars($dataNascimento) . '</strong></p>
                            <p><span>Tipo de Documento:</span> <strong>' . htmlspecialchars($tipoDocumento) . '</strong></p>
                            <p><span>Número do Documento:</span> <strong>' . htmlspecialchars($numeroDocumento) . '</strong></p>
                        </div>
    
                        <!-- Formulário que envia o NUIT para validação -->
                        <form method="POST" action="http://localhost/sportradar/validar_nuit.php">
                            <!-- Campo oculto que envia o NUIT -->
                            <input type="hidden" name="nuit" value="' . htmlspecialchars($nuit) . '">
                            <button type="submit" class="btn-validar">Validar NUIT</button>
                        </form>
                    </div>
    
                </body>
                </html>
            ';
    
            // Define o corpo do email
            $this->mail->Body = $body;
            $this->mail->AltBody = strip_tags($body); // Para email em texto simples
    
            // Envia o email
            $this->mail->send();
            echo 'Email enviado com sucesso!';
        } catch (Exception $e) {
            echo "Email não pode ser enviado. Erro do Mailer: {$this->mail->ErrorInfo}";
        }
    }
    
}
?>

