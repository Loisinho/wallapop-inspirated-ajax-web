<div class="starter-template">
    <form id="signin" class="text-center border border-light p-5" action="./crud.php?op=1" method="post">
        <p class="h4 mb-4">Sign Up</p>
        <div class="form-row mb-4">
            <div class="col">
                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Name" maxlength="30" required>
            </div>
            <div class="col">
                <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Surnames" maxlength="50" required>
            </div>
        </div>
        <input type="text" id="nick" name="nick" class="form-control mb-4" placeholder="User, nick.." required>
        <input type="email" id="email" name="email" class="form-control mb-4" placeholder="E-mail" maxlength="100" required>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" maxlength="125" pattern="(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required>
        <small id="" class="form-text text-muted mb-4">Uppercase, lowercase, number and 6 characters at least.</small>
        <button id="signin-btn" class="btn btn-info my-4 btn-block" type="submit">Sign In</button>
    </form>
</div>
