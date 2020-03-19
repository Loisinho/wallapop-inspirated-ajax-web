<div class="starter-template">
    <form id="makead" class="text-center border border-light p-5" action="./crud.php?op=4" method="post" enctype="multipart/form-data">
        <p class="h4 mb-4">Make Ad</p>
        <div class="form-row mb-4">
            <div class="col">
                <input type="text" id="titulo" name="titulo" class="form-control" placeholder="Title.." maxlength="30" required>
            </div>
        </div>
        <input type="text" id="descripcion" name="descripcion" class="form-control mb-4" placeholder="Description.." required>
        <div class="input-group">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="file" name="img" onchange="writeFileName(this)">
                <label id="filename" class="custom-file-label" for="file">Choose File</label>
            </div>
        </div>
        <script>
            function writeFileName(markup) {
                var name = markup.value.split('\\').pop();
                document.getElementById('filename').innerHTML = name;
            }
        </script>
        <button class="btn btn-info my-4 btn-block" type="submit">Make</button>
    </form>
</div>
