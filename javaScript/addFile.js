import {retrieveFiles} from "./getFiles.js";


$(document).on('submit', '.formAddFile', function (event) {
    event.preventDefault();
    const formAddFile = $(".formAddFile");
    const error = $('.error');
    const modal_title = $('.modal-title');
    const progress_bar = $('.progress-bar');
    const progres = $('.progress');
    const formInpuFile = $('.formInpuFile');
    const replaceFileBtn = $('.replaceFileBtn');
    const abortColl = $('.abortColl');
    $("#exampleModal").modal("show");
    let directId = $('.inputHidden').val();
    let formFile = $('#fileInput');
    let form = formFile[0].files[0]
    formAddFile.hide();

    let typesFile = ['application/zip', 'image/jpeg', 'image/jpg', 'image/gif'];

    if (!typesFile.includes(form.type)) {
        form = [];
        formAddFile.show()
        error.show();
        $("#exampleModal").modal("show")
        modal_title.text('Error');
        error.text('Invalid format');
        return
    } else {


        const chunkSize = 1024 * 1024;

        const totalChunks = Math.ceil(form.size / chunkSize);

        let currentChunk = 0;


        const uploadChunk = function () {
            const start = (currentChunk - 1) * chunkSize;
            const end = Math.min((currentChunk) * chunkSize, form.size);
            let chunk = form.slice(start, end);
            if (currentChunk == 0) {
                chunk = form.slice(0, 1000);

            }
            let formData = new FormData();
            progres.css('display', 'flex');
            formData.append("direction", directId);
            formData.append("fileType", form.type);
            formData.append('file', chunk);
            formData.append('name', form.name);
            formData.append('chunkData', JSON.stringify({currentChunk, totalChunks}));
            let tol = Math.ceil(100 / (totalChunks))
            progress_bar.css('width', currentChunk * tol + '%')

            $.ajax({
                type: "POST",
                url: "php/fileManager.php",
                data: formData,
                processData: false,
                contentType: false,
                xhr: function () {
                    const xhr = new XMLHttpRequest();

                    abortColl.show();

                    abortColl.on('click', function () {
                        xhr.abort();
                        $("#exampleModal").modal("hide")
                        abortColl.hide();
                        progres.hide();
                        progress_bar.css('width', '0%')


                    })


                    return xhr;
                },

                success: function (response) {

                    if (response?.status == false) {
                        if (response.message ) {
                            error.css('display', 'block').text(response.message);
                            $('.addFile').hide();
                            formInpuFile.hide();
                            formAddFile.show();
                            progres.hide();
                            abortColl.hide();
                            replaceFileBtn.show();
                            replaceFileBtn.html("<button class='btn btn-primary replaceFile' value='true'>Yes</button> " +
                                "<button class='btn btn-danger replaceFile' value='false'>No</button>"
                            );
                            $('.replaceFile').on('click', function (event) {
                                if (event.target.value == 'true') {

                                    replaceFileBtn.hide();
                                    modal_title.text('Progres');
                                    ++currentChunk;
                                    uploadChunk();

                                }
                                if (event.target.value == 'false') {
                                    ++currentChunk;
                                    $('.addFile').show();
                                    formInpuFile.show();
                                    error.hide();
                                    replaceFileBtn.hide();
                                    $("#exampleModal").modal("show");

                                }

                            })

                        }
                        formAddFile.show();
                        error.show();
                        $("#exampleModal").modal("show")
                        modal_title.text('Error');
                        error.text(response?.error);
                        progres.hide();
                        progress_bar.css('width', '0');
                        abortColl.hide();
                    } else {
                        modal_title.text('Progres');
                        if (currentChunk < totalChunks) {
                            currentChunk++;
                            error.hide();
                            $('.repiteFileBtn').hide();

                            uploadChunk();
                        }
                        if (response.message == 'File uploaded successfully') {
                            progres.hide();
                            abortColl.hide();
                            progress_bar.css('width', '0%')
                            $("#exampleModal").modal("hide")
                            formInpuFile.val('');
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
    $('.formInpuFile').show().val('');
    $('.sendModel').css('display', 'flex');
    $('.error').hide();
    $('.modal-title').text('Add file');
    $('.deleteModel').hide();
    $(".formAddFile").hide();
    $('.addFile').show();
    $('.replaceFileBtn').hide();
})