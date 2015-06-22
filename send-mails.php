<?php
require_once 'bootstrap.inc';

$message = NULL;
if (!empty($_POST)) {

  $type = (!empty($_POST['type'])) ? $_POST['type'] : FALSE;
  if ($type) {
    $message = 'Method unvalid.';
    switch ($type) {

      case 'simple':
      case 'smtp':
        $mailer = new Mailer($type);
        $message = 'Email failed.';
        if ($mailer->sendEmail()) {
          $message = 'Email successful.';
        }
        break;
    }
  }
}
?>
<html>
  <head>
    <title>PHPHelper - SendMails</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
  </head>
  <body>
    <h1>Send Mails</h1>
    <h3 style="color: red"><?php echo $message ?></h3>
    <div>How to send mails:</div>
    <form method="POST" action="send-mails.php">
      <label>Select method:</label><br/>
      <input type="radio" name="type" value="simple"/>Simple (Mail function)<br/>
      <input type="radio" name="type" value="smtp"/>SMTP (Google)<br/>
      <input type="submit" value="Submit"/>
    </form>
    <ul>
      <li><a href="index.php">Get back homepage</a></li>
    </ul>
  </body>
</html>
