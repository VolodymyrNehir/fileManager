
import {retrieveFiles} from "./getFiles.js";
$(document).on('click', '.deleteFileBtn', function (event) {
    const nameFile = $(this).closest('div').find('span').text();
    const directId = $(this).closest('div').attr('directId');
    const modalTitle = $("#exampleModal .modal-title");

    modalTitle.text('Delete file');
    $('.sendModel').css('display', 'none');
    $('.deleteModel').css('display', 'block');
    $('.deleteModel p').text(`Are you sure you want to delete ${nameFile} ?`);

    $('#exampleModal').data('nameFile', nameFile);
    $('#exampleModal').data('directId', directId);
    $('#exampleModal').data('event',event);

    $("#exampleModal").modal("show");


})
$(document).on('click', '.btnDeleteFileYes', function () {
    const nameFile =$('#exampleModal').data('nameFile');
    const directId = $('#exampleModal').data('directId');
    const event = $('#exampleModal').data('event');
    $.ajax({
        type: 'POST',
        url: 'php/deleteFile.php',
        data: {'nameFile': nameFile, 'directId': directId},
        dataType: 'json',

        success: function (response) {
            $("#exampleModal").modal("hide");

            $(event.target).closest('div').remove();
            if ($('.containerFiles .file').length == 0){


                let li = $('.befClick').parent().prev('li')
                $('.befClick').remove();
                li.find('a').addClass('befClick').click();

                retrieveFiles(directId,parseInt($('.befClick').text()),parseInt($('.pagination li .page')[0]?.innerText,10))

            } else {

                retrieveFiles(directId,parseInt($('.befClick').text(),10),$('.page')[0]?.innerText)
            }

        }
    })
});