import {pagination} from "./pagination.js";

export function loadPagination(response, pageStartr=1, thisPage=1) {

        const filesOnPage = 10;

        let pages = Math.ceil(response / filesOnPage);
    if (pages <= 3 && thisPage == 3 ){
        pageStartr = 1;
    }
        function paginationLost() {
            $('.pagination').empty();
            let pagination = document.createElement('ul').classList.add('pagination');

    for (let i = pageStartr; i <= pages; i++) {
        if (i == (3 + pageStartr)) break;
        $('.pagination').append(`
                                    <li class="page-item "><a id="page${i}" class=" page-link page" aria-label="page" href="#">${i}</a></li>
                `)
    }



            if (pages > 3) {
                $('.pagination').prepend(`
             <li class="page-item">
                        <a class="page-link previusPage" href="#" aria-label="Previous">
                            <span aria-hidden="true"  aria-label="Previous">&laquo;</span>
                        </a>
                    </li>`)

                $('.pagination').append(`
             <li class="page-item">
                        <a class="page-link nextPage" href="#" aria-label="Next">
                            <span aria-hidden="true" aria-label="Next">&raquo;</span>
                        </a>
                    </li>`)
            }

            $("[aria-label='Page']").html(pagination);
            $(`#page${thisPage}`).addClass('befClick')

        }


        paginationLost();

}