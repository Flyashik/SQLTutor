<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: /');
}
?>
<?php
if ($_POST) {

    require "connect_db.php";

    $name = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST['name']))));
    $surname = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST['surname']))));
    $login = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST['login']))));
    $email = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST['email']))));
    $password = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST['password']))));
    $password_confirm = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST['password_confirm']))));
    if (!preg_match("/^[A-Za-z0-9]+$/", $login))
    {
        $_SESSION['errors']['login'] = "Логин должен содержать латинские буквы и цифры";
    }
    if (!preg_match("/^[A-Za-z0-9]+$/", $password))
    {
        $_SESSION['errors']['password'] = "Пароль должен содержать латинские буквы и цифры";
    }
    if ($password != $password_confirm) {
        $_SESSION['errors']['password_confirm'] = "Пароли не совпадают!";
    }
    if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email))
    {
        $_SESSION['errors']['email'] = "Не подходящий адрес почты";
    }

    $hash_pass = password_hash($password, PASSWORD_DEFAULT);
    $hash = md5($login . time());

    $prepare = mysqli_prepare($conn, "SELECT * FROM `users` WHERE `e-mail` = ? OR `login` = ?");
    mysqli_stmt_bind_param($prepare, "ss", $email, $login);
    mysqli_stmt_execute($prepare);
    $meta = mysqli_stmt_result_metadata($prepare);
    while ($field = mysqli_fetch_field($meta)) {
        $params[] = &$row[$field->name];
    }

    $email_confirmed = 1;

    call_user_func_array(array($prepare, 'bind_result'), $params);

    while (mysqli_stmt_fetch($prepare)) {
        foreach ($row as $key => $val) {
            $c[$key] = $val;
        }
        $user[] = $c;
    }
    mysqli_stmt_close($prepare);
    if (isset($user)) {
        $_SESSION['errors']['user'] = "Пользователь с таким логином или почтой уже существует";
    }
    if (isset($_SESSION['errors'])) {
        header('Location: ../sign-up.html');
    } else if (!isset($_SESSION['errors'])) {
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
                <p>Что бы подтвердить Email, перейдите по <a href="http://f0682541.xsph.ru/blocks/confirmed.php?hash=' . $hash . '">ссылке</a></p>
                </body>
                </html>
                ';

        $prepare = mysqli_prepare($conn, "INSERT INTO `users` (`first-name`, `second-name`, `login`, `e-mail`, `password`, `hash`, `email_confirmed`) VALUES (?, ?, ?, ?, ?, ?, ?);");
        mysqli_stmt_bind_param($prepare, "ssssssi", $name, $surname, $login, $email, $hash_pass, $hash, $email_confirmed);
        mysqli_stmt_execute($prepare);
        $last_id = mysqli_insert_id($conn);
        mysqli_stmt_close($prepare);
        $prepare = mysqli_prepare($conn, "INSERT INTO `users_test_results` (`user_id`) VALUES (?)");
        mysqli_stmt_bind_param($prepare, "s", $last_id);
        mysqli_stmt_execute($prepare);
        mysqli_stmt_close($prepare);
        
        if (mail($email, "Подтвердите Email на сайте SQLTutor", $message, $headers)) {
            echo 'На Ваш почтовый ящик отправлено письмо с подтверждением почты.';
        }
    }
    mysqli_close($conn);
}
?>