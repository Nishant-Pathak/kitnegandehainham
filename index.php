<?php
include_once("includes/header.php");
include_once("includes/navbar.php");

?>
    <link href="./css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <script src="./js/fileinput.min.js" type="text/javascript" ></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script type="text/javascript" src="../js/kitnegandehainham.js"></script>
        <script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js" ></script>

      <!-- Begin page content -->
      <div class="container">
        <div class="page-header">
          <h1>Let's find how dirty we are...</h1>
          <p>Mark and rate the place in your neighbour which you find dirty.</p>
        </div>
        <div id="map-canvas"></div>
      </div>
    </div>

<?php

include_once("includes/footer.php");
?>
