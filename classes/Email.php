<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    //envia confirmación de creación de cuenta, al usuario, por email
    public function enviarConfirmacion() {

        //configuración servidor SMTP para PHPMailer de mailtrap.io
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        
        //información para el usuario
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Confirma tu cuenta de Uptask.com';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en Uptask.com, solo debes confirmarla en el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no creaste esta cuenta, ignora este mensaje</p>";
        $contenido .= '</html>';

        // Cuerpo del mensaje
        $mail->Body =$contenido;

        // Enviar el email
        $mail->send();

    }

    //envia instrucciones resetear contraseña, al usuario, por email
    public function enviarInstrucciones() {
    
        //configuración servidor SMTP para PHPMailer de mailtrap.io
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        
        //información para el usuario
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Reestablece tu Password de Uptask.com';
    
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';
    
        //Usa la sintaxis .= para contatenar el contenido de $contenido, en varias lineas.
        //La superglobal $_ENV['APP_URL] contiene el valor de la variable de entorno APP_Url
        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado resetear tu Passoword de Uptask.com, solo debes confirmarlo en el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/restablecer?token=" . $this->token . "'>Reestablecer Password</a></p>";
        $contenido .= "<p>Si tu no creaste esta cuenta, ignora este mensaje</p>";
        $contenido .= '</html>';
    
        // Cuerpo del mensaje
        $mail->Body =$contenido;
    
        // Enviar el email
        $mail->send();
    
    }
}