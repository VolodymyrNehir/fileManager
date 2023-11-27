import {retrieveFiles} from "./getFiles.js";

$(document).on('submit', '.formAddFile', function (event) {
    event.preventDefault();

    $("#exampleModal").modal("show");
    let directId = $('.inputHidden').val();
    let formFile = $('#fileInput');
    let form = formFile[0].files[0]
    $(".formAddFile").css("display", 'none')

    let typesFile = ['application/zip', 'image/jpeg', 'image/jpg', 'image/gif'];

    if (!typesFile.includes(form.type)) {
        form = [];
        $(".formAddFile").css("display", 'block')
        $('.error').css('display', 'block')
        $("#exampleModal").modal("show")
        $('.modal-title').text('Error');
        $('.error').text('Invalid format');
        return
    } else {


        const chunkSize = 1024 * 1024;

        const totalChunks = Math.ceil(form.size / chunkSize);

        let currentChunk = 0;

        const xhr = new XMLHttpRequest();

        xhr.open('HEAD', `uploads/${directId}/${form.name}`, true);

        xhr.onreadystatechange = function () {

            if (xhr.readyState === 4 && xhr.status === 200 && currentChunk == 0) {
                $('.error').css('display', 'block').text('The file exists, do you want to replace it?');
                $('.addFile').css('display', 'none');
                $('.formInpuFile').css('display', 'none');
                $(".formAddFile").css("display", 'block')
                $('.progress').css('display', 'none');
                $('.abortColl').css('display', 'none');
                $('.repiteFileBtn').css('display', 'block');
                $('.repiteFileBtn').html("<button class='btn btn-primary repitFile' value='true'>Yes</button> " +
                    "<button class='btn btn-danger repitFile' value='false'>No</button>"
                );
                $('.repitFile').on('click', function (event) {
                    if (event.target.value == 'true') {

                        $('.repiteFileBtn').css('display', 'none');
                        $('.modal-title').text('Progres');
                        uploadChunk();

                    } else if (event.target.value == 'false') {
                        ++currentChunk;
                        xhr.abort();
                        $('.addFile').css('display', 'block');
                        $('.formInpuFile').css('display', 'block');
                        $('.error').css('display', 'none')
                        $('.repiteFileBtn').css('display', 'none');
                        $("#exampleModal").modal("show");


                    }

                })
            } else if (xhr.status !== 200) {
                uploadChunk()

            }
        };

        xhr.send();


        const uploadChunk = function () {
            const start = currentChunk * chunkSize;
            const end = Math.min((currentChunk + 1) * chunkSize, form.size);

            const chunk = form.slice(start, end);
            let formData = new FormData();
            $('.progress').css('display', 'flex');
            formData.append("direction", directId);
            // formData.append("fileType", form.type);
            formData.append('file', chunk);
            formData.append('name', form.name);
            formData.append('chunkData', JSON.stringify({currentChunk, totalChunks}));
            let tol = Math.ceil(100 / (totalChunks))
            $('.progress-bar').css('width', currentChunk * tol + '%')

            $.ajax({
                type: "POST",
                url: "php/fileManager.php",

                data: formData,
                processData: false,
                contentType: false,
                xhr: function () {
                    const xhr = new XMLHttpRequest();

                    $('.abortColl').css('display', 'block');

                    $('.abortColl').on('click', function () {
                        xhr.abort();
                        $("#exampleModal").modal("hide")
                        $('.abortColl').css('display', 'none');
                        $('.progress').css('display', 'none');
                        $('.progress-bar').css('width', '0%')


                    })


                    return xhr;
                },

                success: function (response) {

                    if (response?.status == false) {

                        $(".formAddFile").css("display", 'block')
                        $('.error').css('display', 'block')
                        $("#exampleModal").modal("show")
                        $('.modal-title').text('Error');
                        $('.error').text(response?.error);
                        $('.progress').css('display', 'none');
                        $('.progress-bar').css('width', '0');
                        $('.abortColl').css('display', 'none');
                    } else {
                        $('.modal-title').text('Progres');
                        if (currentChunk < totalChunks - 1) {
                            currentChunk++;
                            $('.error').css('display', 'none');
                            $('.repiteFileBtn').css('display', 'none');
                            uploadChunk();
                        }
                        if (response.message == 'File uploaded successfully') {
                            $('.progress').css('display', 'none');
                            $('.abortColl').css('display', 'none');
                            $('.progress-bar').css('width', '0%')
                            $("#exampleModal").modal("hide")
                            $('.formInpuFile').val('');
                            if ($('.containerFiles .file').length == 0 && $('.befClick').text() < 2) {
                                $(`#${directId}`).click();

                            } else {
                                retrieveFiles(directId, parseInt($('.befClick').click().text()), parseInt($('.pagination li .page')[0]?.innerText));


                            }

                        }

                    }


                }
            });
        }

    }
})
$(document).on('click', '.btnAdd', function () {
    $('.formInpuFile').css('display', 'block').val('');
    $('.sendModel').css('display', 'flex');
    $('.error').css('display', 'none')
    $('.modal-title').text('Add file');
    $('.deleteModel').css('display', 'none');
    $(".formAddFile").css("display", 'block');
    $('.addFile').css('display', 'block');
    $('.repiteFileBtn').css('display', 'none');
})