<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["file"])) {
        $chunkData = json_decode($_POST['chunkData']);
        $direct = $_POST['direction'];
//        $fileType = $_POST['fileType'];
        $file = $_FILES["file"];
        $name = $_POST["name"];
        $typesFile = ['application/zip', 'image/jpeg', 'image/jpg', 'image/gif'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $typeFile = $finfo->file($_FILES['file']['tmp_name']);
        $nameAndType = substr_replace(basename($name), '', strrpos(basename($name), '.')) . '.' . substr($typeFile, strpos($typeFile, '/') + 1);
        session_start();


        if ($chunkData->currentChunk == 0) {
            $ceshFile = scandir('../temporarilyLoaded/');
            foreach ($ceshFile as $file) {
               if (isset($file) ) {
                    unlink('../temporarilyLoaded/' . $file);
                }
            }
            $_SESSION['nameAndType'] = $nameAndType;

        }
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

        if (file_exists("../uploads/$direct/" . $_SESSION['nameAndType']) && $chunkData->currentChunk == 0) {
            header('Content-Type: application/json');
            exit(json_encode(['status' => false, 'message' => 'The file exists, do you want to replace ' . $_SESSION['nameAndType'] . '?'
                ]
            ));
        }
        $chunk = file_get_contents($_FILES['file']['tmp_name']);

        $uploadDirectory = "../uploads/$direct";


        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }


        fopen($_SESSION['filePath'], 'ab');
        $filePath = $uploadDirectory . '/' . $_SESSION['nameAndType'];


        if (file_exists("../uploads/$direct/" . $_SESSION['nameAndType']) && $chunkData->currentChunk == 1) {
            file_put_contents('../temporarilyLoaded/' . $_SESSION['nameAndType'], $chunk);
        } else if (!file_exists("../uploads/$direct/" . $_SESSION['nameAndType']) && $chunkData->currentChunk == 1) {
            file_put_contents('../temporarilyLoaded/' . $_SESSION['nameAndType'], $chunk);
        } else if ($chunkData->currentChunk >= 1) {

            file_put_contents('../temporarilyLoaded/' . $_SESSION['nameAndType'], $chunk, FILE_APPEND);
        }


        if ($chunkData->currentChunk == $chunkData->totalChunks) {
            fopen($filePath, 'ab');

            copy(('../temporarilyLoaded/' . $_SESSION['nameAndType']), ("../uploads/$direct/" . $_SESSION['nameAndType']));
            unlink('../temporarilyLoaded/' . $_SESSION['nameAndType']);
            unset($_SESSION['filePath']);
            header('Content-Type: application/json');
            echo json_encode(['status' => true, 'message' => 'File uploaded successfully'
                ]
            );
            fclose("../uploads/$direct/" . $_SESSION['nameAndType']);

        }


    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => false, 'error' => 'File not found in the request']);
    }
}


?>