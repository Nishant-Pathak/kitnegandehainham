<?php
include_once("includes/header.php");
include_once("includes/navbar.php");

if(!isset($_SESSION["user"])){
    header("Location: /");
    exit(0);
}

?>
      <!-- Begin page content -->
      <div class="container">
        <div class="page-header">
          <h1>Pending verification</h1>
        </div>
        <?php
            include_once("includes/dbinit.php");
            $query = "SELECT * FROM marker_tbl where 1";
            $result = mysqli_query($conn, $query);
            $i =0;
            echo '<div class="container-fluid"><div class="row">';
            while ($row = $result->fetch_assoc()) {
                if($row["verified"] > 0) continue;
                echo '<div class="modal-body col-md-3 ">'.
                    '<img style="max-width:100%" src= '.'uploads/'.$row["mid"].'></img>'.
                    '<pre>Severity:'.$row["Severe"].
                    '<div class="pull-right"><button onclick=verifyImg('.$row["Lat"].','.$row["Lng"].',1'.
                    ')>Verify</button><button onclick=verifyImg('.$row["Lat"].','.$row["Lng"].',0'.
                    ')>Deny</button></div></pre></div>';
                if($i%3 == 0 && $i!=0) echo '</div><div class="row">';
                $i++;
            }
            echo "</div>";
            include_once("includes/dbclose.php");
        ?>
      </div>
    </div>
    <script>
    function verifyImg(Lat,Lng,vd) {
        console.log(Lat,Lng,vd);
        var postdata = {};
        postdata.Lat = Lat;
        postdata.Lng = Lng;
        postdata.vd  = vd;
        $.post("nitro/nitro.php?action=verify", postdata).done(function(data) {
            if(typeof data == "string") data = jQuery.parseJSON(data);
            if((data.errorcode != 0) || (data.message != "Done")) {
                alert(data.message);
            }
        return;
        });
    }

    </script>
<?php

include_once("includes/footer.php");
?>
