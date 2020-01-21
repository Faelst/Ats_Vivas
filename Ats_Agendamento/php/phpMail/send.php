<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

//require 'vendor/autoload.php';

$mail = new PHPMailer(true);


try {
    //Server settings
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'vivas.ats@gmail.com';                  // SMTP username
    $mail->Password   = 'V1V45ADM';                             // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipientsternet.com.br', 'Joe User');     // Add a recipient
    //$mail->addAddress('karine@viv
    $mail->setFrom('vivas.ats@gmail.com', 'Sistema AT&s');
    $mail->addAddress('rafael.silverio@vivasinternet.com.br', 'Rafael Silverio');     // Add a recipient
    //$mail->addAddress('matheus.marinho@vivasinternet.com.br', 'Matheus Marinho');     // Add a recipient


    // Attachments
    //$mail->addAttachment('../../img/favicon.png');         // Add attachments


    $mail->addAttachment("Retorio_ATS(19-" . date('M') . "-" . date('Y') . ").pdf");    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Relatorios de Pontuacao (' . date('M') . "-" . date('Y') . ')';
    $mail->Body    = '<h3>Sistema At&s (TESTE)</h3><br>
                      <p>Funcionalidade que encaminha automaticamente o relatorio de pontuação.</p>  
                      <p>Developed by @Rafael Silverio</p>';
    $mail->AltBody = 'Developed by @Rafael Silverio';

    $mail->send();

    echo 'E-mail enviado com sucesso !';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
