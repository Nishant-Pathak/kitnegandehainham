<?php
include_once("includes/header.php");
include_once("includes/navbar.php");

?>
    <link href="./css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <script src="./js/fileinput.min.js" type="text/javascript" ></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <?php
        echo print_r($_GET,true);
        if(isset($_GET["debugJs"]) and $_GET["debugJs"] == true) {
            echo '<script type="text/javascript" src="../js/kitnegandehainham.js"></script>';
        } else {
            echo '<script type="text/javascript" src="../js/kitnegandehainham.min.js"></script>';
        }
    ?>
    <script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js" ></script>

      <!-- Begin page content -->
      <div class="container">
        <div class="page-header">
          <blockquote class="blockquote-reverse">
            <p>Many faces ... One voice ... One solution ...</p>
            <footer>&#8212; Swachh Bharat Abhiyan</footer>
          </blockquote>
          <p>Mark and rate the place in your neighbour which you find filthy. Allow access to your device location to place the marker or manually place by holding and dragging it. You can view previously marked location in view tab.</p>
        </div>
        <div class="alert alert-success in fade" role="alert" style="display:none">
            <button type="button" id="alertclose" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <span id="alerttext"></span>
        </div>
        <div id="map-canvas"></div>
        
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button id="closebtn1" type="button" class="close" data-dismiss="modal">
                  <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"></h4>
              </div>
              <div class="modal-body">
                <div class="progress">
                  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    0%
                  </div>
                </div>
              <div id="formdiv">
                <form class="form-horizontal" id="markerForm" enctype="multipart/form-data" method="POST">
                
              <div class="form-group form-inline">
                <label class="col-sm-4">Location Cordinates</label>
                <div class="form-group col-sm-4">
                    <input type="text" class="form-control" name="Lat" id="Lat" value="" readonly>
                </div>
                <div class="form-group col-sm-4">
                    <input type="text" class="form-control" name="Lng" id="Lng" value="" readonly>
                </div>
              </div>
              <div class="form-group form-inline">
              <label class="col-sm-4">Less</label>
                <label class="radio-inline">
                    <input type="radio" name="Severe" id="inlineRadio1" value=1> 1
                </label>
                <label class="radio-inline">
                    <input type="radio" name="Severe" id="inlineRadio2" value=2 checked> 2
                </label>
                <label class="radio-inline">
                    <input type="radio" name="Severe" id="inlineRadio3" value=3> 3
                </label>
                <label class="radio-inline">
                    <input type="radio" name="Severe" id="inlineRadio4" value=4> 4
                </label>
                <label class="radio-inline">
                    <input type="radio" name="Severe" id="inlineRadio5" value=5> 5
                </label>
                <label class="col-sm-2 pull-right">Most</label>
              </div>
              <div class="form-group form-inline" id="SelectM">
                <label class="col-sm-4">Mode</label>
                <label class="radio-inline">
                    <input type="radio" name="SelectMode" value=1 checked>Click New Picture
                </label>
                <label class="radio-inline">
                    <input type="radio" name="SelectMode" value=2>Browse
                </label>
              </div>
              <div class="form-group form-inline" id="showCamera">
                <label class="col-sm-4">Picture</label>
                <input type="file" name="dirtyPic"  class="col-sm-2" id="dirtyPic" accept="image/*" capture="camera">
              </div>
              <div id="recaptcha_div"class="form-group"></div>
              </form>

              </div>
              </div>
              <div class="modal-footer">
                <button id="closebtn" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button id="savebtn"  type="button" class="btn btn-default btn-success" >Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- container-->

<?php

include_once("includes/footer.php");
?>
