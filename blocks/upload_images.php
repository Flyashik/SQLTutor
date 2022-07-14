<?php
session_start();
require "connect_db.php";
function getRandomFileName($path)
{
    $path = $path ? $path . '/' : '';
    do {
        $name = md5(microtime() . rand(0, 9999));
        $file = $path . $name;
    } while (file_exists($file));
    return $name;
}
if (isset($_FILES['image'])) {
    $tempFileName = $_FILES['image']['tmp_name'];
    $codeError = $_FILES['image']['error'];
    if ($codeError !== UPLOAD_ERR_OK || !is_uploaded_file($tempFileName)) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE   => 'Размер файла превысил значение upload_max_filesize в конфигурации PHP.',
            UPLOAD_ERR_FORM_SIZE  => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE в HTML-форме.',
            UPLOAD_ERR_PARTIAL    => 'Загружаемый файл был получен только частично.',
            UPLOAD_ERR_NO_FILE    => 'Файл не был загружен.',
            UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
            UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
            UPLOAD_ERR_EXTENSION  => 'PHP-расширение остановило загрузку файла.',
        ];
        $unknownMessage = 'При загрузке файла произошла неизвестная ошибка.';
        $outputMessage = isset($errorMessages[$codeError]) ? $errorMessages[$codeError] : $unknownMessage;
        setcookie('error', $outputMessage, time() + 1, "/");
        header('Location: /edit-profile.html');
        die();
    } else {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = (string) finfo_file($fileInfo, $tempFileName);
        if (strpos($mimeType, 'image') === false) {
            $outputMessage = 'Можно загружать только изображения.';
            setcookie('error', $outputMessage, time() + 1, "/");
            header('Location: /edit-profile.html');
            die();
        }
        $image = getimagesize($tempFileName);
        $maxSize  = 1920 * 1080 * 5;
        $maxWidth  = 1920;
        $maxHeight = 1080;
        if (filesize($tempFileName) > $maxSize) {
            $outputMessage = 'Размер изображения не должен превышать 50 Мбайт.';
            setcookie('error', $outputMessage, time() + 1, "/edit-profile.html");
            header('Location: /edit-profile.html');
            die();
        }
        if ($image[1] > $maxHeight) {
            $outputMessage = 'Высота изображения не должна превышать 1080 точек.';
            setcookie('error', $outputMessage, time() + 1, "/");
            header('Location: /edit-profile.html');
            die();
        }
        if ($image[0] > $maxWidth) {
            $outputMessage = 'Ширина изображения не должна превышать 1920 точек.';
            setcookie('error', $outputMessage, time() + 1, "/");
            header('Location: /edit-profile.html');
            die();
        }
        $name = getRandomFileName($tempFileName);
        $extension = image_type_to_extension($image[2]);
        $format = str_replace('jpeg', 'jpg', $extension);
        if (!move_uploaded_file($tempFileName, '../upload/' . $name . $format)) {
            $outputMessage = 'При записи изображения на диск произошла ошибка.';
            setcookie('error', $outputMessage, time() + 1, "/");
            header('Location: /edit-profile.html');
            die();
        }
        $full_name_image = $name . $format;
        foreach ($_SESSION['user'] as $key => $value) {
            $key = htmlspecialchars($key);
            $value = htmlspecialchars($value);
            $user[$key] = $value;
        }
        if ($user['image_url'] != 'user-default.png')
            unlink('../upload/' . $user['image_url']);
        $id = $user['id'];
        $prepare = mysqli_prepare($conn, "UPDATE `users` SET `image_url` = ? WHERE `id` = ?");
        mysqli_stmt_bind_param($prepare, "si", $full_name_image, $id);
        mysqli_stmt_execute($prepare);
        mysqli_stmt_close($prepare);
        mysqli_close($conn);
        $_SESSION['user']['image_url'] = $full_name_image;
        header('Location: /edit-profile.html');
    }
};
