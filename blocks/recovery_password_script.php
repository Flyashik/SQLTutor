<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Курс по SQL</title>
  <link rel="stylesheet" type="text/css" href="../css/reset.css">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <link rel="stylesheet" type="text/css" href="../css/adaptive.css">
</head>

<?php
if($_POST['email'])
{
    require 'connect_db.php';

    $email = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST['email']))));

    $hash = md5($email . time());

    $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "To: <$email>\r\n";
        $headers .= "From: <tutorsql545@f0682541.xsph.ru>\r\n";
        $message = '
                <html>
                <head>
                <title>Подтвердите Email</title>
                </head>
                <body>
                <p>Для восстановления пароля перейдите по <a href="http://f0682541.xsph.ru/blocks/update_password.php?hash=' . $hash . '">ссылке</a></p>
                </body>
                </html>
                ';
    $prepare = mysqli_prepare($conn, 'SELECT `e-mail` FROM `users` WHERE `e-mail` = ?');
    mysqli_stmt_bind_param($prepare, 's', $email);
    mysqli_stmt_execute($prepare);
    mysqli_stmt_bind_result($prepare, $db_email);
    mysqli_stmt_fetch($prepare);
    mysqli_stmt_close($prepare);
    if(isset($db_email))
    {
        $prepare = mysqli_prepare($conn, 'UPDATE `users` SET `hash` = ? WHERE `e-mail` = ?');
        mysqli_stmt_bind_param($prepare, 'ss', $hash, $email);
        mysqli_stmt_execute($prepare);
        mysqli_stmt_close($prepare);

        if (mail($email, "Восстановление пароля на SQLTutor", $message, $headers)) {
            echo '<p>Ссылка для восстановления пароля отправлена на Вашу почту</p>';
        } else {
            echo 'При отправке письма возникла ошибка';
        }
    }
    else
    {
        $_SESSION['errors']['email'] = "Пользователя с такой почтой не существует";
        header('Location: ../recovery-password.html');
    }

    unset($_POST['email']);
}
else
{
    header('Location: ../sign-in.html');
}