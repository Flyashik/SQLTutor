<?php
    session_start();
    require "connect_db.php";
    foreach ($_SESSION['user'] as $key => $value) {
        $key = htmlspecialchars($key);
        $value = htmlspecialchars($value);
        $user[$key] = $value;
    }
    $id = $user['id'];
    if($user['image_url'] != 'user-default.png')
    {
        $prepare = mysqli_prepare($conn, "UPDATE `users` SET `image_url` = 'user-default.png' WHERE `id` = ?");
        mysqli_stmt_bind_param($prepare, "i", $id);
        mysqli_stmt_execute($prepare);
        mysqli_stmt_close($prepare);
        unlink('../upload/' . $user['image_url']);
        $_SESSION['user']['image_url'] = 'user-default.png';
    }
    mysqli_close($conn);
    header('Location: /edit-profile.html');
