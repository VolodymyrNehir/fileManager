<div class="modal" tabindex="-1" id="exampleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add file</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class=" form-control-sm modelContent">
                <div class="sendModel">
                    <input class="inputHidden" type="hidden">
                    <form class="formAddFile" action="php/fileManager.php" method="post" enctype="multipart/form-data">
                        <label for="formFile" class="form-label">Add file</label>
                        <input class="formInpuFile form-control" type="file" accept="application/zip, image/jpeg, image/jpg, image/gif" name="file" id="fileInput">
                        <p class="error">Error</p>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary addFile">
                        </div>

                    </form>

                </div>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" ></div>
                </div>
                <div class="deleteModel">
                    <div class="modal-body">
                        <p></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btnDeleteFileYes btn-danger">Delete</button>
                        <button type="button" class="btn  btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>

                <div class=" mt-3 abortColl">
                    <button type="button" class="btn btn-danger ">Stop</button>
                </div>
            </div>

        </div>
    </div>
</div>