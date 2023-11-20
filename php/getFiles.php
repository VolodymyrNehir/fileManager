<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['direction'])) {
    $direct = $_POST['direction'];
    $uploadDirectory = "uploads/$direct/";
    if (is_dir($uploadDirectory)) {
        $filesPerPage = 10;
        $currentPage = isset($_POST['page']) ? $_POST['page'] : 1;
        $start = ($currentPage - 1) * $filesPerPage;

        $filesData = [];
        $dirContents = scandir($uploadDirectory);

        $filteredFiles = array_filter($dirContents, function ($file) {
            return $file != "." && $file != "..";
        });

        $pagedFiles = array_slice($filteredFiles, $start, $filesPerPage);

        foreach ($pagedFiles as $file) {
            $filePath = $uploadDirectory . $file;
            $fileInfo = pathinfo($filePath);

            $fileSize = filesize($filePath);
            $fileCreationTime = filectime($filePath);
            $fileModificationTime = filemtime($filePath);

            $filesData[] = [
                'dirContentsCount' =>count($filteredFiles),
                'direction' => $direct,
                'name' => $fileInfo['filename'],
                'extension' => $fileInfo['extension'],
                'size' => $fileSize,
                'creation_time' => date("Y-m-d H:i:s", $fileCreationTime),
                'modification_time' => date("Y-m-d H:i:s", $fileModificationTime)
            ];
        }

        $jsonData = json_encode($filesData);

        header('Content-Type: application/json');
        echo $jsonData;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Directory not found']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => false, 'error' => 'Invalid request method11']);
}
?>
