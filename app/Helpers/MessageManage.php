<?php


namespace App\Helpers;

use App\Models\User;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MessageManage
{

    private string $imageHead = '<div style="width: 100%">
                          <img src="https://i.postimg.cc/kMQYqcyG/logo.png" alt="ico logo"
                                style="width: 650px; height: 200px; margin: 0 auto; display: block">
                        </div>';

    public function __construct()
    {
    }

    /**
     * @param        $info
     * @return string
     */
    function templateEmailForgot($info): string
    {
        $template = $this->getHeaderTemplate() . $this->imageHead;

        $token = md5(uniqid($info->email, true));
        User::query()->whereEmail($info->email)->update(
            [
                'token_password_forgot' => $token
            ]
        );

        $url = config('app.url');

        $head     = "<div class='content-center'><h2 class='title'>¿Una nueva contraseña {$info->name}?</h2>";
        $p1       = "<p>No te preocupes, crea una nueva contraseña.</p>";
        $p2       = "<a href='{$url}/nuevo-password/{$info->email}?token=$token'
                        target='_blank'
                        class='button-link'
                    >
                          Crear contraseña
                    </a>";
        $p3       = "<p>Si no has solicitado un cambio ignora este mensaje.</p>";
        $p4       = "<p>Tu contraseña continuará siendo la misma.</p></div>";
        $template = $template . $head . $p1 . $p2 . $p3 . $p4;

        return $template . '</body></html>';
    }

    /**
     * @param        $info
     * @return string
     */
    function templateEmailVerify($info): string
    {
        $template = $this->getHeaderTemplate() . $this->imageHead;

        $token = md5(uniqid($info->email, true));
        User::query()->whereEmail($info->email)->update(
            [
                'token_email_verify' => $token
            ]
        );

        $url = config('app.url');

        $head     = "<div class='content-center'><h2 class='title'>Gracias por usar nuestro servicio {$info->name}?</h2>";
        $p2       = "<a href='{$url}/verify-email/{$info->email}?token=$token'
                        target='_blank'
                        class='button-link'
                    >
                          Verificar email
                    </a>";
        $p4       = "<p>Tu email será verificado.</p></div>";
        $template = $template . $head . $p2 . $p4;

        return $template . '</body></html>';
    }

    /**
     * @param        $info
     * @return string
     */
    function templateWelcomePassword($info): string
    {
        $template = $this->getHeaderTemplate() . $this->imageHead;

        $url     = config('app.url');
        $appName = config('app.name');

        $head     = "<div class='content-center'><h2 class='title'>Gracias por usar nuestro servicio {$info->name}?</h2>";
        $p2       = "<a href='$url'
                        target='_blank'
                        class='button-link'
                    >
                          $appName
                    </a>";
        $p4       = "<p>Tu contraseña es {$info->password} para poder entrar en la plataforma.</p></div>";
        $template = $template . $head . $p2 . $p4;

        return $template . '</body></html>';
    }

    /**
     * @param string $subject
     * @param string $message
     * @param array | string $emailRecipient
     * @param null $attachment
     * @return bool
     * @throws Exception
     */
    function sendEmail(string $subject, string $message, array|string $emailRecipient, $attachment = null): bool
    {
        // Settings
        $mail = new PHPMailer(true); // create a new object
        $mail->IsSMTP(); // enable SMTP
        /*$mail->SMTPDebug  = 2; // debugging: 1 = errors and messages, 2 = messages only*/
        $mail->SMTPAuth   = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Port       = 465; // or 587
        $mail->Host       = config('app.config.host');
        $mail->IsHTML(true);
        $mail->Username = config('app.config.sender');
        $mail->Password = config('app.config.password');
        $mail->SetFrom(config('app.config.sender'), config('app.config.name_user'));
        $mail->Subject  = $subject;
        $mail->Body     = $message;
        $mail->CharSet  = 'UTF-8';
        $mail->Encoding = 'base64';
        if (isset($attachment)) {
            $mail->AddAttachment(
                $attachment['content'],
                $name = $attachment['name'],
                $encoding = 'base64',
                $type = 'application/pdf'
            );
        }
        if (is_string($emailRecipient)) {
            $mail->AddAddress($emailRecipient);
        } else if (is_array($emailRecipient)) {
            foreach ($emailRecipient as $item) {
                $mail->AddAddress($item);
            }
        }

        return $mail->Send();
    }

    /**
     * @return string
     */
    private function getHeaderTemplate(): string
    {
        return '<!DOCTYPE html>
                <html lang="en">
                <head>
                  <meta charset="UTF-8">
                  <title>Title</title>
                  <style>
                    .title {
                      color: #004087;
                      font-size: 24px;
                      font-weight: 900;
                      margin: 20px 0 20px;
                    }

                    p {
                      font-size: 18px;
                      margin: 0;
                    }

                    div.user-pass {
                      margin: 0 auto;
                      padding-left: 20px;
                      padding-top: 20px;
                      width: 300px;
                    }

                    div.content-center {
                      margin: 0 auto;
                      max-width: 500px;
                      text-align: center;
                    }

                    a.button-link {
                      background: #004087;
                      border-radius: 5px;
                      color: white !important;
                      display: inline-block;
                      font-size: 18px;
                      margin: 30px 0 20px;
                      padding: 12px 100px 18px;
                      text-decoration: none !important;
                    }

                    table {
                      border-collapse: separate;
                      border-spacing: 0;
                      border-width: 1px 0 0 1px;
                      margin: 0 0 30px;
                      table-layout: fixed;
                      width: 100%;
                    }

                    table td.first-row {
                      font-weight: bold;
                    }

                    table td, table th {
                      border-top: 1px solid #dee2e6;
                      font-size: 14px;
                      padding: .75rem;
                      text-align: left;
                    }
                  </style>
                </head>
                <body>';
    }
}
