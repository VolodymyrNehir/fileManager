export function displayData(response) {
    const containerFiles = $('.containerFiles');
   containerFiles.html('');
    let divContainerFiles = document.createElement('div');

    for (let i = 0; i < response.length; i++) {
            $(divContainerFiles).append(`
                            <div class="file" directId="${response[i].direction}">
                <i class="bi bi-file-earmark"></i>
<div class="d-inline-flex">
 <span class="nameFile">${response[i].name}</span>
<span>.${response[i].extension}</span>
</div>

                <button class="deleteFileBtn btnIcon">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>`)


        containerFiles.html(divContainerFiles);
    }
}