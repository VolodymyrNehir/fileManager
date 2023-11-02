<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["file"])) {
        $file = $_FILES["file"];
        $typesFile =['application/zip', 'image/jpeg','image/jpg','image/gif'];

        if (!in_array( $file['type'] ,$typesFile)) {
           exit('bad file');
        }
        $uploadDirectory = "uploads/";
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $targetFile = $uploadDirectory . basename($file["name"]);
       echo $file['type'];
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            echo "Файл успішно завантажено";
        } else {
            echo "Помилка під час завантаження файлу";
        }
    } else {
        echo "Файл не був завантажений";
    }
}
?>
