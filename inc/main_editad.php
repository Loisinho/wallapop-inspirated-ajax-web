<div class="starter-template">

    <form id="editad" class="text-center border border-light p-5" action="./crud.php?op=5" method="post" enctype="multipart/form-data">
        <p class="h4 mb-4">Edit Ad</p>
        <div class="form-row mb-4">
            <div class="col">
                <input type="text" id="titulo" name="titulo" value="" class="form-control" placeholder="Title.." maxlength="30" required>
            </div>
        </div>
        <input type="text" id="descripcion" name="descripcion" value="" class="form-control mb-4" placeholder="Description.." required>
        <input class="form-control mb-4" type="hidden" id="oldimg" name="oldimg" value="">
        <img id="print" class="mb-4 border" src="" alt="NO IMG" width="200" height="100">
        <div class="input-group">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="file" name="img" onchange="writeFileName(this)">
                <label id="filename" class="custom-file-label" for="file">Choose File</label>
            </div>
        </div>
        <script>
            function writeFileName(fileinput) {
                var name = fileinput.value.split('\\').pop();
                document.getElementById('filename').innerHTML = name;
            }
        </script>
        <input class="form-control mb-4" type="hidden" id="id" name="id" value="">
        <button class="btn btn-info my-4 btn-block" type="submit">Edit</button>
    </form>
                    
</div>
