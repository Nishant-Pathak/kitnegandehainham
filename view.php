<?php
include_once("includes/header.php");
include_once("includes/navbar.php");

?>
      <!-- Begin page content -->
      <div class="container">
        <div class="page-header">
          <h2>How filthy we are</h2>
        </div>
        <div><p class="lead">Zoom in to the red marks to view.</p></div>
        <div id="map-canvas"></div>
      </div>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <?php
        echo print_r($_GET,true);
        if(isset($_GET["debugJs"]) and $_GET["debugJs"] == true) {
            echo '<script type="text/javascript" src="../js/kitnegandehainham.js"></script>';
        } else {
            echo '<script type="text/javascript" src="../js/kitnegandehainham.min.js"></script>';
        }
    ?>
<?php

include_once("includes/footer.php");
?>
