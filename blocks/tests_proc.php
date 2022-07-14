<?php

session_start();

if ($_POST) {

    require 'connect_db.php';
    foreach ($_SESSION['user'] as $key => $value) {
        $key = htmlspecialchars($key);
        $value = htmlspecialchars($value);
        $user[$key] = $value;
    }
    $id = $user['id'];

    $user_answers = $_POST;
    $form_id = $_POST["form_id"];

    $answers_data = file_get_contents("../data/test" .  $form_id . ".json");
    $object = json_decode($answers_data);
    $right_answers = (array) $object;

    $sum = 0;

    $result = [];

    unset($user_answers['form_id']);

    foreach ($right_answers as $key => $value) {
        if ($value == $user_answers[$key]) {
            $sum += 1;
            $result[$key] = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="#52ff33" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg> Верный ответ.';
        } else
            $result[$key] = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Неверный ответ.';
    }

    $check = mysqli_query($conn, "SELECT `test_$form_id` FROM `users_test_results` WHERE `user_id` = '$id'");
    $check = mysqli_fetch_assoc($check);
    if($sum > $check["test_$form_id"])
        mysqli_query($conn, "UPDATE `users_test_results` SET `test_$form_id` = '$sum' WHERE `user_id` = '$id'");
        $_SESSION['user']["test_$form_id"] = $sum;
    mysqli_close($conn);

    $string_sum = "Ваш результат: $sum из " . count($right_answers) . ".";

    foreach($result as $key => $value)
    {
        setcookie("result[$key]", $value, time() + 1, "/");
    }
    setcookie("result[sum]", $string_sum, time() + 1, "/");

    header("Location: ../test-$form_id.html");
}
?>
