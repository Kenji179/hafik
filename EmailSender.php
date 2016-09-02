<?php

require_once 'vendor/autoload.php';

abstract class EmailSender
{
    public static function send($to, $subject, $message, $from = 'rezervace@skolkahafik.cz', $stringAttachment = null) {
        $mail = new PHPMailer();
        $mail->CharSet = 'utf-8';
        $mail->isHTML(true);

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = getenv('ZOHO_SMPT');                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = getenv('ZOHO_USERNAME');                // SMTP username
        $mail->Password = getenv('ZOHO_PASSWORD');                  // SMTP password
        $mail->SMTPSecure = getenv('ZOHO_SECURITY_TYPE');                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;

        $mail->setFrom($from);
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;

        if (!is_null($stringAttachment)) {
            $mail->addStringAttachment($stringAttachment, $subject . '.pdf');
        }

        if (!$mail->send()) {
            file_put_contents('log.txt', date('d.m.Y H:i:s') . ': ' . $mail->ErrorInfo, FILE_APPEND);
            return false;
        } else {
            return true;
        }
    }
}
