<?php
require 'gmail/EmailSender.php'; // Certifique-se de que o caminho para a classe está correto

// Criando uma instância da classe EmailSender
$emailSender = new EmailSender();

// Dados fictícios para teste
$to = 'edylsondossantos02@gmail.com';  // Email do destinatário
$toName = 'John Doe'; 
$subtitulo = "Confirmação de Criação de NUIT";
$subject = htmlspecialchars($subtitulo);     // Nome do destinatário
$subject = 'Confirmação de Criação de NUIT';  // Assunto do email
$userName = 'John Doe';    // Nome do usuário
$nuit = '123456789';       // Número fictício do NUIT
$endereco = 'Rua Principal, 123';  // Endereço fictício
$dataNascimento = '1990-01-01';    // Data de nascimento fictícia
$tipoDocumento = 'BI';             // Tipo de documento fictício
$numeroDocumento = 'AB123456';     // Número de documento fictício

// Chamando o método sendEmail com os dados fictícios
$emailSender->sendEmail($to, $toName, $subject, $userName, $nuit, $endereco, $dataNascimento, $tipoDocumento, $numeroDocumento);
