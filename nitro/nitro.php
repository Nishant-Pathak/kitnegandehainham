<?php

include_once("nitro_response.php");

session_start();

$request_uri = $_SERVER["REQUEST_URI"];

$obj = new nitro();

call_user_func_array(array($obj,"processReq"), array());

class nitro{

   private $response = null;

   public function __construct(){
      $this->response = new nitro_response();
   }

   public function processReq(){
      $args = $_GET;
      switch($args["action"]){
         case "saveMarker":
            $this->saveMarker();
         break;
         case "getMarker":
            $this->getMarker();
         break;
         default:
         break;
      }
      $this->send_response();
   }
   private function getMarker() {
      include_once("../includes/dbinit.php");
      $query = "SELECT * FROM marker_tbl";
      $result = mysqli_query($conn, $query);
      $result2;
      $i = 0;
      while ($row = $result->fetch_assoc()) {
         $result2[$i]["Lat"] = $row["Lat"];
         $result2[$i]["Lng"] = $row["Lng"];
         $i++;
      }
    //  print $result2;
      $this->response->add_response("markers", $result2);
      include_once("../includes/dbclose.php");
   }

   private function saveMarker() {
      include_once("../includes/dbinit.php");
      $Lat = mysqli_real_escape_string($conn, $_POST["Lat"]);
      $Lng = mysqli_real_escape_string($conn, $_POST["Lng"]);
      $query = "SELECT * FROM marker_tbl WHERE Lat=".$Lat." AND Lng=".$Lng;
      $result = mysqli_query($conn, $query);
      if($result->num_rows != 0) {
          error_log("point already inserted");
          $this->response->set_message("point already marked in database");
          $this->response->set_errorcode(-1);
          return;
      }
      $insert_q = "INSERT INTO marker_tbl (Lat, Lng) VALUES(".$Lat.", ".$Lng.")";
      $result = mysqli_query($conn, $insert_q);
      include_once("../includes/dbclose.php");
   }
   private function send_response(){
      header("Content-type: application/json; charset=utf-8");
      print $this->response->to_string();
   }
}
?>
