<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["file"])) {
        $chunkData = json_decode($_POST['chunkData']);
        $direct = $_POST['direction'];
        $file = $_FILES["file"];
        $name = $_POST["name"];
        $fileType = $_POST['fileType'];
        $typesFile = ['application/zip', 'image/jpeg', 'image/jpg', 'image/gif'];
        if (!in_array($fileType, $typesFile)) {
            header('Content-Type: application/json');
            exit(json_encode([
                    'status' => false,
                    'error' => 'Invalid format'
                ]
            ));
        }
            $chunk = file_get_contents($_FILES['file']['tmp_name']);

            $uploadDirectory = "../uploads/$direct";

            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0755, true);
            }

            $filename = $name;

            $filePath = $uploadDirectory . '/' . basename($name);

            fopen($filePath, 'ab');

        file_put_contents($filePath, $chunk, FILE_APPEND);


            if ($chunkData->currentChunk == $chunkData->totalChunks - 1) {
                header('Content-Type: application/json');
                echo json_encode(['status' => true, 'message' => 'File uploaded successfully'
                    ]
                );
            } else if (!move_uploaded_file($file["tmp_name"], $filePath)) {
                header('Content-Type: application/json');
                echo json_encode(['status' => false,
                    'error' => 'An error occurred while uploading the file']);
            }  else {
                header('Content-Type: application/json');
                echo json_encode(['status' => true]);
            }

        fclose($filePath);

    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => false, 'error' => 'File not found in the request']);
    }
}


?>