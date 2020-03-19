<div class="starter-template">
    <form id="reset" class="text-center border border-light p-5" action="./crud.php?op=8" method="post">
        <p class="h4 mb-4">Reset Password</p>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" maxlength="125" pattern="(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required>
        <input class="form-control mb-4" type="hidden" id="email" name="email" value="<?php echo($_POST['email']) ?>">
        <input class="form-control mb-4" type="hidden" id="token" name="token" value="<?php echo($_POST['token']) ?>">
        <button class="btn btn-info my-4 btn-block" type="submit">Reset</button>
    </form>
</div>
