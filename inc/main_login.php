<div class="starter-template">
    <form id="login" class="text-center border border-light p-5" action="./crud.php?op=2" method="post">
        <p class="h4 mb-4">Log In</p>
        <input type="text" id="user" name="user" class="form-control mb-4" placeholder="Nick or Email" required>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" maxlength="125" pattern="(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required>
        <a id="forgot-link" href="index.php?load=forgot">forgot password?</a>
        <button class="btn btn-info my-4 btn-block" type="submit">Go</button>
    </form>
</div>
