<?php if (substr_count($_SERVER[‘HTTP_ACCEPT_ENCODING’], ‘gzip’)) ob_start(“ob_gzhandler”); else ob_start(); 
      session_cache_limiter("public");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="X-Frame-Options" content="deny">
    <link rel="shortcut icon" href="../images/favicon.png">

    <title>Kitne Gande Hain Hum</title>

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/sticky-footer-navbar.css" rel="stylesheet">
    <link href="../css/kitnegandehainham.css" rel="stylesheet">
    <?php
        $path = substr($_SERVER['REQUEST_URI'],1);
        if($path == "about.php") echo '<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5203f34c20d9f163" async="async"></script>';
    ?>
  </head>

  <body>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- Wrap all page content here -->
    <div id="wrap">
