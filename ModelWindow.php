<div class="modal" tabindex="-1" id="exampleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="mb-3 form-control-sm">
                <form class="formAddFile" action="fileManager.php" method="post" enctype="multipart/form-data">
                    <label for="formFile" class="form-label">Default file input example</label>
                    <input class="form-control" type="file"  name="file" id="fileInput">
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary addFile"`>
                    </div>
                </form>

                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: "></div>
                </div>
            </div>

        </div>
    </div>
</div>