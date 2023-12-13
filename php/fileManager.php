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



        if ($chunkData->currentChunk == 0) {
            $ceshFile = scandir('../temporarilyLoaded/');
            foreach ($ceshFile as $file) {
               if (isset($file) ) {
                    unlink('../temporarilyLoaded/' . $file);
                }
            }

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

        if (file_exists("../uploads/$direct/" . basename($name)) && $chunkData->currentChunk == 0) {
            header('Content-Type: application/json');
            exit(json_encode(['status' => false, 'message' => 'The file exists, do you want to replace ' . basename($name) . '?'
                ]
            ));
        }
        $chunk = file_get_contents($_FILES['file']['tmp_name']);

        $uploadDirectory = "../uploads/$direct";


        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }



        $filePath = $uploadDirectory . '/' . basename($name);


        fopen("../temporarilyLoaded/" . basename($name), 'ab');

        if (file_exists("../uploads/$direct/" . basename($name)) && $chunkData->currentChunk == 1) {
            file_put_contents('../temporarilyLoaded/' . basename($name), $chunk);
        } else if (!file_exists("../uploads/$direct/" . basename($name)) && $chunkData->currentChunk == 1) {
            file_put_contents('../temporarilyLoaded/' . basename($name), $chunk);
        } else if ($chunkData->currentChunk >= 1) {

            file_put_contents('../temporarilyLoaded/' . basename($name), $chunk, FILE_APPEND);
        }


        if ($chunkData->currentChunk == $chunkData->totalChunks) {
            fopen($filePath, 'ab');
            copy(('../temporarilyLoaded/' . basename($name)), ("../uploads/$direct/" . basename($name)));
            unlink('../temporarilyLoaded/' . basename($name));
            header('Content-Type: application/json');
            echo json_encode(['status' => true, 'message' => 'File uploaded successfully'
                ]
            );
            fclose("../uploads/$direct/" . basename($name));

        }


    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => false, 'error' => 'File not found in the request']);
    }
}


?>