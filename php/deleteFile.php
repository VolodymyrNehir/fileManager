<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['directId']) && isset($_POST['nameFile'])) {
        $directId = $_POST['directId'];
        $nameFile = $_POST['nameFile'];
        $dir = "uploads/$directId/$nameFile";
        $uploadDirectory = "uploads/$directId/";

        $dirContents = scandir($uploadDirectory);

        $filteredFiles = array_filter($dirContents, function ($file) {
            return $file != "." && $file != "..";
        });
        if (file_exists($dir)) {
            unlink($dir);
            echo json_encode(['status' => true,
                'dirContentsCount' => count($filteredFiles)-1]);
        } else {
            echo json_encode(['status' => false, 'message' => 'File not found']);
        }
    } else {
        echo json_encode(['status' => false, 'message' => 'Missing required parameters']);
    }


} else {
    echo json_encode(['status' => false, 'message' => 'Invalid request method22']);
}
?>