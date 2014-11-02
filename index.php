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
          <blockquote class="blockquote-reverse">
            <p>Many faces ... One voice ... One solution ...</p>
            <footer>&#8212; Swachh Bharat Abhiyan</footer>
          </blockquote>
          <p>Mark and rate the place in your neighbour which you find filthy. Allow access to your device location to start. You can view previously marked location in view tab.</p>
        </div>
        <div id="map-canvas"></div>
      </div>
    </div>

<?php

include_once("includes/footer.php");
?>
