<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mailer
 *
 * @author kenneth
 */
class Mailer {

  protected $type;
  protected $to_mail = 'cliente.regular.anexusit@gmail.com';
  protected $from_mail = 'webmaster@example.com';

  public function __construct($type = NULL) {
    $this->type = ($type) ? $type : NULL;
  }

  public function sendEmail() {
    if (is_null($this->type)) {
      return FALSE;
    }
    elseif ($this->type == 'smtp') {
      return $this->useSMTP();
    }

    return $this->useMail();
  }

  protected function useSMTP() {
    require_once $_SERVER["DOCUMENT_ROOT"] . 'libraries/PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer();  // create a new object
//    $mail->SMTPDebug = 1; // Enable debug mode.

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $this->to_mail;                 // SMTP username
    $mail->Password = '!135790.';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    $mail->From = 'from@example.com';
    $mail->FromName = 'Mailer';
    $mail->addAddress($this->to_mail, 'Kenneth Bolivar');     // Add a recipient

    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = 'Here is the subject';
    $mail->Body = 'This is the HTML message body <b>in bold!</b>';

    return $mail->Send();
  }

  protected function useMail() {
    $subject = 'Custom subject';
    $message = 'Hello Email!';
    return mail($this->to_mail, $subject, $message, 'From: webmaster@example.com');
  }

}
