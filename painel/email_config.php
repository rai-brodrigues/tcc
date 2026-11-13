
<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../vendor/autoload.php';

function criarMailer()
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username = 'raisao34mb@gmail.com';
    $mail->Password   = 'jnas mvie acuo lqsj';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->CharSet = 'UTF-8';
    $mail->setFrom('raisao34mb@gmail.com', 'FitControl');

    return $mail;
}
