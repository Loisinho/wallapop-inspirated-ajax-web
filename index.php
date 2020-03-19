<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>WALLAPUSH</title>

    <!-- favicon.ico -->
    <link rel="shortcut icon" type="image/png" href="#"/>
      
    <!-- Bootstrap core CSS -->
    <link href="public/css/bootstrap.css" rel="stylesheet">
    
    <!-- Bootstrap 4 Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    <!-- Custom styles for this template -->
    <link href="public/css/starter-template.css" rel="stylesheet">
    <!-- jquery.js -->
    <script src="public/js/jquery.js"></script>

    <!-- <script>window.jQuery || document.write('<script src="/docs/4.3/assets/js/vendor/jquery-slim.min.js"><\/script>')</script> -->
    <script src="public/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>

    <!-- app.js -->
    <script src="public/js/app.js"></script>

  </head>

  <body style="background: #efefef">

    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <?php
        require_once 'inc/navbar_default.php'; 
      ?>
    </nav>
  
    <main role="main" class="container">
      <?php
        if (isset($_POST['reset']))
          require_once 'inc/main_recover.php';
        else
          require_once 'inc/main_default.php'; 
      ?>
    </main>

  </body>
</html>
