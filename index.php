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
    <link href="main.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<div class="container-fluid ">
    <div class="row">
        <div class=" col-md-3">

            <div class="container fileDirect">
                <div id="1">
                    <i class="bi bi-folder2-open"></i>
                    <span>file1</span>
                    <button class="btnAdd m-2 btnIcon" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus-circle-fill"></i></button>
                </div>
            </div>
            <div class="container fileDirect">
                <div id="2">
                    <i class="bi bi-folder2-open"></i>
                    <span>file2</span>
                    <button class="btnAdd m-2 btnIcon" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus-circle-fill"></i></button>
                </div>
            </div>
            <div class="container fileDirect">
                <div id="3">
                    <i class="bi bi-folder2-open"></i>
                    <span>file3</span>
                    <button class="btnAdd m-2 btnIcon" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus-circle-fill"></i></button>
                </div>
            </div>
            <div class="container fileDirect">
                <div id="4">
                    <i class="bi bi-folder2-open"></i>
                    <span>file4</span>
                    <button class="btnAdd m-2 btnIcon" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus-circle-fill"></i></button>
                </div>
            </div>

        </div>
        <div class=" containerFile container col-md-9">

        </div>
    </div>

</div>
<?php require 'ModelWindow.php' ?>
<?php require 'fileManager.php'?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz"
        crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
    $(function () {
        $('.formAddFile').on('submit',function (even) {
            even.preventDefault()

            let formFile = $('#fileInput');
            let form = formFile[0].files[0]
            console.log(this);

            let typesFile =['application/zip', 'image/jpeg','image/jpg','image/gif'];

            if (!typesFile.includes(form.type)) {
                alert('bad file');
                form = [];
            } else {
                alert('good file')
            }
let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "fileManager.php",
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
const xhr = new XMLHttpRequest();
xhr.upload.onprogress = function (event) {
   if (event.lengthComputable) {
       let progress = (event.loaded / event.total) * 100;
       console.log(progress);
       $('.progress-bar').css('width', progress.toFixed() + '%')
   }
}

                    return xhr;
$('#exampleModal').modal('hide')
                },
                success: function(response) {
                    $('.progress-bar').css('width', '0%')
                    console.log(response);
                }
            });


        })

        $('.fileDirect').on('click', function (even) {
            if (!even.target.closest('i')) {
                $('.containerFile').html(`
               <div class="file">
                <i class="bi bi-file-earmark"></i>
                <span>file</span>
                <button class="deleteFile btnIcon">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
                `)
            }



        })
    })

</script>
</body>
</html>