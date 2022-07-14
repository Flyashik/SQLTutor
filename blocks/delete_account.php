<?php

session_start();

require 'connect_db.php';

$id = $_SESSION['user']['id'];

if($_SESSION['user']['image_url'] != 'user-default.png'){
    unlink('../upload/' . $_SESSION['user']['image_url']);
}

$prepare = mysqli_prepare($conn, 'DELETE FROM `users` WHERE `id` = ?');
mysqli_stmt_bind_param($prepare, 'i', $id);
mysqli_stmt_execute($prepare);
mysqli_stmt_close($prepare);

unset($_SESSION['user']);

header('Location: ../index.html');