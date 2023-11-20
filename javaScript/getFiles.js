import {loadPagination} from './loadPagination.js';
import {displayData} from "./displayData.js";
import {pagination} from "./pagination.js";

    $(document).on('click', '.folder', function (event) {
        $('.folder').data('event', event);
        const idDirect = $(this).find('div').attr('id');
        $('.inputHidden').val(idDirect);
        $(".formAddFile").css("display", 'block')
        $('.progress').css('display', 'none');
        $('.abortColl').css('display', 'none');
            if (!event.target.closest('i')) {
                $('.folderList div').removeClass('active');
                $(this).addClass('active');

                retrieveFiles(idDirect)

            }


    })



export function retrieveFiles(idDirect, currentPage = 1, startPage = 1) {
    const event = $('.folder').data('event');
    $.ajax({
        type: 'POST',
        url: 'php/getFiles.php',
        data: {'direction': idDirect, 'page': currentPage},
        dataType: 'json',
        success: function (response) {
            if (!event.target.closest('i')) {
                displayData(response);

                pagination(idDirect, response[0]?.dirContentsCount);

                loadPagination(response[0]?.dirContentsCount, startPage, currentPage);

                $('.pagination li .page').removeClass('befClick');
                $(`#page${currentPage}`).addClass('befClick');
            }


        }
    })
}

