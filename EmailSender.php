<?php

require_once 'vendor/autoload.php';

abstract class EmailSender
{
    public static function send($to, $subject, $message, $from = 'rezervace@skolkahafik.cz', $stringAttachment = null) {
        $mail = new PHPMailer();
        $mail->CharSet = 'utf-8';
        $mail->isHTML(true);

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
