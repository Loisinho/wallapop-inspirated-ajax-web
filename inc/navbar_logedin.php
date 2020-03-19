<a id="wallapush" class="navbar-brand active" href="/wallapush-ajax">WALLAPUSH</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarsExampleDefault">
  <ul class="navbar-nav mr-auto">
    <li class="nav-item dropdown">
      <span id="dropdown01" class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></span>
      <div class="dropdown-menu" aria-labelledby="dropdown01">
        <a id="logout-link" class="dropdown-item text-dark" href="./crud.php?op=3"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <a id="delaccount-link" class="dropdown-item text-danger" style="cursor: pointer;" data-toggle="modal" data-backdrop="true" data-target="#delete_modal"><i class="fas fa-skull-crossbones"></i> Delete Account</a>
      </div>
    </li>
    <li class="nav-item">
      <a id="myads-link" class="nav-link" href="wallapush-ajax/index.php?load=myads">My Ads</a>
    </li>
    <li class="nav-item">
      <a id="makead-link" class="nav-link" href="wallapush-ajax/index.php?load=makead">Make Ad</a>
    </li>
  </ul>
  <form class="form-inline my-2 my-lg-0" action="wallapush-ajax/index.php" method="GET">
    <input type="hidden" name="load" value="adslist">
    <input class="form-control mr-sm-2" type="text" name="filter" placeholder="Ad" aria-label="Search">
    <button id="search-btn" class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
  </form>
</div>
