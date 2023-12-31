<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="css/main.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<div class="container-fluid ">
    <div class="row">
        <div class="folderList col-md-3">
            <?php
            $dir = 'uploads';
                $handle = opendir($dir);
            while (($folder = readdir($handle)) !== false) {
                if ($folder !== '.' &&  $folder !== '..' && $folder !== '.DS_Store'  && is_dir("uploads/$folder")) {?>
                     <div class="container folder">
                <div id="<?php echo $folder ?>">
                    <i class="bi bi-folder2-open"></i>
                    <span><?php echo $folder?></span>
                    <button class="btnAdd m-2 btnIcon" data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                class="bi bi-plus-circle-fill"></i></button>
                </div>
            </div>
               <?php  }
            }
            closedir($dir);
            ?>

        </div>
        <div class="container col-md-9">
            <div class=" containerFiles" directId="">

            </div>
            <nav aria-label="Page navigation example" class="m-2">
                <ul class="pagination">

                </ul>
            </nav>

        </div>
    </div>

</div>

<?php require 'ModelWindow.php' ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz"
        crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>

<script type="module" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="module" src="javaScript/addFile.js"></script>
<script type="module" src="javaScript/deleteFile.js"></script>
<script type="module" src="javaScript/getFiles.js"></script>

<script>


</script>
</body>
</html>
