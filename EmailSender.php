<?php

require_once 'vendor/autoload.php';

abstract class EmailSender
{
    public static function send($to, $subject, $message, $from = 'rezervace@skolkahafik.cz', $stringAttachment = null) {
        $mail = new PHPMailer();
        $mail->CharSet = 'utf-8';
        $mail->isHTML(true);

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'roman@swdesign.cz';                 // SMTP username
        $mail->Password = 'Ap[x(2e?bmepG5_';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;

        $mail->setFrom($from);
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;

        if (!is_null($stringAttachment)) {
            $mail->addStringAttachment($stringAttachment, $subject . '.pdf');
        }

        return $mail->send();
    }
}
