<a id="wallapush" class="navbar-brand active" href="/wallapush-ajax">WALLAPUSH</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault"aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarsExampleDefault">
  <ul class="navbar-nav mr-auto">
    <li class="nav-item">
      <a id="signin-link" class="nav-link" href="./index.php?load=signin">Sign In</a>
    </li>
    <li class="nav-item">
      <a id="login-link" class="nav-link" href="./index.php?load=login">Log In</a>
    </li>
  </ul>
  <form class="form-inline my-2 my-lg-0" action="./index.php" method="GET">
    <input type="hidden" name="load" value="adslist">
    <input class="form-control mr-sm-2" type="text" name="filter" placeholder="Ad" aria-label="Search">
    <button id="search-btn" class="btn btn-secondary my-2 my-sm-0 text-white" type="submit">Search</button>
  </form>
</div>
