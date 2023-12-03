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




        const uploadChunk = function () {
            const start = (currentChunk -1) * chunkSize;
            const end = Math.min((currentChunk) * chunkSize, form.size);
          let  chunk = form.slice(start, end);
if (currentChunk == 0) {
    chunk = form.slice(0, 1000);

}
            let formData = new FormData();
            $('.progress').css('display', 'flex');
            formData.append("direction", directId);
            formData.append("fileType", form.type);
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
if (response?.message =='The file exists, do you want to replace it?') {
    $('.error').css('display', 'block').text('The file exists, do you want to replace it?');
    $('.addFile').css('display', 'none');
    $('.formInpuFile').css('display', 'none');
    $(".formAddFile").css("display", 'block')
    $('.progress').css('display', 'none');
    $('.abortColl').css('display', 'none');
    $('.replaceFileBtn').css('display', 'block');
    $('.replaceFileBtn').html("<button class='btn btn-primary replaceFile' value='true'>Yes</button> " +
        "<button class='btn btn-danger replaceFile' value='false'>No</button>"
    );
    $('.replaceFile').on('click', function (event) {
        if (event.target.value == 'true') {

            $('.replaceFileBtn').css('display', 'none');
            $('.modal-title').text('Progres');
            ++currentChunk;
            uploadChunk();

        }  if (event.target.value == 'false') {
            ++currentChunk;
            $('.addFile').css('display', 'block');
            $('.formInpuFile').css('display', 'block');
            $('.error').css('display', 'none')
            $('.replaceFileBtn').css('display', 'none');
            $("#exampleModal").modal("show");

        }

    })

                    }
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
                        if (currentChunk < totalChunks) {
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

        uploadChunk();

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
    $('.replaceFileBtn').css('display', 'none');
})