import { loadPagination } from "./loadPagination.js";
import { displayData } from "./displayData.js";

export function pagination(idDirect, dirContentsCount) {
    const currentPage = 10;
    $('.pagination li .page')[0]?.classList.add('befClick');

    $(document).off('click', '.pagination li a').on('click', '.pagination li a', function (event) {
        let pages = Math.ceil(dirContentsCount / currentPage);
        let page = 1;
        page = parseInt($('.befClick').text());


        if (event.target.getAttribute('aria-label') === 'Next') {
            if ((+$('.pagination li a').last()?.text()) === pages || $('.page').length == 2) return;

            $("[aria-label='page']").each(function () {
                let pagesBtn = parseInt($(this).text());
                if (pages === pagesBtn) return false;

                $(this).text(pagesBtn + 1);
            });

            if (page < pages) ++page;
        } else if (event.target.getAttribute('aria-label') === 'Previous') {
            let pagesBtn = 1;
            $("[aria-label='page']").each(function () {
                pagesBtn = parseInt($(this).text());
                if (pagesBtn === 1) return false;

                $(this).text(pagesBtn - 1);
            });

            if (page > 1) --page;

            $(`#page${page}`).addClass('befClick');
            $('.pagination li .page')[page - 1]?.classList.add('befClick');
        } else if (event.target.getAttribute('aria-label') === 'page') {
            $(this).addClass('befClick');
            page = parseInt($(this).text());
        }


        $.ajax({
            type: 'POST',
            url: 'php/getFiles.php',
            data: { 'direction': idDirect, 'page': page },
            dataType: 'json',
            success: function (response) {
                if (response.length !== 0) {
                    loadPagination(response[0]?.dirContentsCount, parseInt($('.pagination li .page')[0]?.innerText,10), page);
                    displayData(response);
                }
            }
        });
    });
}
