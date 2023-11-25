<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["file"])) {
        $chunkData = json_decode($_POST['chunkData']);
        $direct = $_POST['direction'];
        $file = $_FILES["file"];
        $name = $_POST["name"];
        $typesFile = ['application/zip', 'image/jpeg', 'image/jpg', 'image/gif'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
       $typeFile = $finfo->file($_FILES['file']['tmp_name']);
       if ($chunkData->currentChunk == 0) {
           if (!in_array($typeFile, $typesFile)) {
               header('Content-Type: application/json');
               exit(json_encode([
                       'status' => false,
                       'error' => 'Invalid format'
                   ]
               ));
           }
       }


        $chunk = file_get_contents($_FILES['file']['tmp_name']);

            $uploadDirectory = "../uploads/$direct";


            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0755, true);
            }

            $filename = $name;

            $filePath = $uploadDirectory . '/' . basename($name);

            fopen($filePath, 'ab');
        if (file_exists("../uploads/$direct/".basename($name)) && $chunkData->currentChunk == 0) {
            file_put_contents($filePath, $chunk);
            } else {
            file_put_contents($filePath, $chunk, FILE_APPEND);
        }



            if ($chunkData->currentChunk == $chunkData->totalChunks - 1) {
                header('Content-Type: application/json');
                echo json_encode(['status' => true, 'message' => 'File uploaded successfully'
                    ]
                );
            }


        fclose($filePath);

    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => false, 'error' => 'File not found in the request']);
    }
}


?>