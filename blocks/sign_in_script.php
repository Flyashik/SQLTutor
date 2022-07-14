<?php

session_start();

if(isset($_SESSION['user']))
{
    header('Location: /');
}

if ($_POST) {

    $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
    $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
    $sign_form_id = $_POST['sign_form_id'];

    require 'connect_db.php';

    $prepare = mysqli_prepare($conn, "SELECT * FROM `users` LEFT JOIN `users_test_results` ON `users`.`id` = `users_test_results`.`user_id` WHERE `users`.`login` = ?");
    mysqli_stmt_bind_param($prepare, "s", $login);
    mysqli_stmt_execute($prepare);
    $meta = mysqli_stmt_result_metadata($prepare);
    while ($field = mysqli_fetch_field($meta)) {
        $params[] = &$row[$field->name];
    }
    call_user_func_array(array($prepare, 'bind_result'), $params);
    while (mysqli_stmt_fetch($prepare)) {
        foreach ($row as $key => $val) {
            $c[$key] = $val;
        }
        $user[] = $c;
    }
    mysqli_stmt_close($prepare);

    mysqli_close($conn);

    var_dump($user);

    if (is_null($user) || !password_verify($password, $user[0]['password'])) {
        $_SESSION['errors']['logpass'] = "Неверное имя пользователя или пароль";
        if($sign_form_id == 0)
            header('Location: ../sign-in.html');
        else
            header("Location: ../test-$sign_form_id.html");

    } else {
        foreach ($user[0] as $name => $value) {
            $_SESSION['user'][$name] = $value;
        }

        if($sign_form_id == 0)
        {
            header('Location: /');
        }
        else
        {
            header("Location: ../test-$sign_form_id.html");
        }
    }
}
