<?php
ob_start("ob_gzhandler");
include_once("nitro_response.php");
session_start();
$request_uri = $_SERVER["REQUEST_URI"];
$obj = new nitro();
call_user_func_array(array($obj,"processReq"), array());

class nitro{

   private $response = null;
   private $target_dir = "../uploads";
   public function __construct(){
      $this->response = new nitro_response();
   }

   public function processReq(){
      $args = $_GET;
      switch($args["action"]){
         case "saveMarker":
            $this->saveMarker();
         break;
         case "getMarkers":
            $this->getMarkers();
         break;
         case "getImage":
            $this->getImage();
         break;
         case "login":
            $this->login();
         break;
         case "verify":
            $this->verify();
         break;
         default:
         break;
      }
      $this->send_response();
   }

   private function verify() {
      include_once("../includes/dbinit.php");
      $Lat = mysqli_real_escape_string($conn, $_POST["Lat"]);
      $Lng = mysqli_real_escape_string($conn, $_POST["Lng"]);
      $vd  = mysqli_real_escape_string($conn, $_POST["vd"]);
      $query = "SELECT * FROM marker_tbl WHERE Lat=".$Lat." AND Lng=".$Lng;
      $result = mysqli_query($conn, $query);
      
      if($result->num_rows == 0) {
          $this->response->set_message("Given cordinate not found.");
          $this->response->set_errorcode(-1);
          return 0;
      }
      $row = $result->fetch_assoc();
      if($row["verified"] !=0) {
          $this->response->set_message("Already verified.");
          $this->response->set_errorcode(-1);
          return 0;
      }
      if($vd == 1) $query = "UPDATE marker_tbl SET verified=1 WHERE mid=".$row["mid"];
      else {
      //  delete("../uploads/".$row["mid"]);
        $query = "DELETE FROM marker_tbl WHERE mid=".$row["mid"];
      }
      mysqli_query($conn, $query);
      include_once("../includes/dbclose.php");
      return 0;
   }
   
   private function login() {
      include_once("../includes/dbinit.php");
      $uname = mysqli_real_escape_string($conn, $_POST["UserName"]);
      $pass = mysqli_real_escape_string($conn, $_POST["Password"]);
      $query = "SELECT * FROM user_tbl WHERE username='".$uname."' AND password='".$pass."'";
      $result = mysqli_query($conn, $query);
      include_once("../includes/dbclose.php");
      if($result->num_rows == 0) {
        $this->response->set_message("User not found.");
        $this->response->set_errorcode(-1);
        return 0;
      }
      $row = $result->fetch_assoc();
      $_SESSION["user"] = $uname;
      $_SESSION["authlevel"] = $row["authlevel"];
      header("Location: /");
      exit(0);
   }
   
   private function getImage() {
      include_once("../includes/dbinit.php");
      $Lat = mysqli_real_escape_string($conn, $_GET["Lat"]);
      $Lng = mysqli_real_escape_string($conn, $_GET["Lng"]);
      $query = "SELECT * FROM marker_tbl WHERE Lat=".$Lat." AND Lng=".$Lng;
      $result = mysqli_query($conn, $query);
      include_once("../includes/dbclose.php");

      if($result->num_rows == 0) {
          $this->response->set_message("Given cordinate not found.");
          $this->response->set_errorcode(-1);
          return 0;
      }
      $row = $result->fetch_assoc();

      $img    = "../uploads/".$row["mid"];
      $cord   = "(".$Lat.",".$Lng.")";
      $imgdiv = '<div class="modal-body">'.
                '<pre id="cord">'.$cord.'</pre>'.
                '<img style="max-width:100%" src= '.$img.'></img>'.
                '<pre>Severity:'.$row["Severe"].'</pre></div>';
      $this->response->add_response("imgdiv", $imgdiv);

   }
   
   private function getMarkers() {
      include_once("../includes/dbinit.php");
      $query = "SELECT * FROM marker_tbl where 1";
      $result = mysqli_query($conn, $query);
      $result2;
      $i = 0;
      while ($row = $result->fetch_assoc()) {
         $result2[$i]["Lat"] = $row["Lat"];
         $result2[$i]["Lng"] = $row["Lng"];
         $result2[$i]["Severe"] = $row["Severe"];
         $i++;
      }
      $this->response->add_response("markers", $result2);
      include_once("../includes/dbclose.php");
   }

   private function verifyReCaptha() {
      if(!isset($_POST["recaptcha_challenge_field"]) || !isset($_POST["recaptcha_response_field"])) {
        $this->response->set_message("Missing reCAPTCHA");
        $this->response->set_errorcode(-1);
        return false;
      }
      $chal = $_POST["recaptcha_challenge_field"];
      $crsp = $_POST["recaptcha_response_field"];
      require_once('../includes/recaptchalib.php');
      $privatekey = "6LeRZvwSAAAAALNr-n6o2-4h6_dYJihT86EjgpnA";
      $resp = recaptcha_check_answer ($privatekey,
                          $_SERVER["REMOTE_ADDR"],
                          $_POST["recaptcha_challenge_field"],
                          $_POST["recaptcha_response_field"]);

      if (!$resp->is_valid) {
        $this->response->set_message("The reCAPTCHA wasn't entered correctly. Try it again. Error :". $resp->error);
        $this->response->set_errorcode(-1);
        return false;
      }
      return true;
   }

   private function checkForFileErrors() {
       $emsg = "";
       if(count($_FILES) > 1) $emsg = "Multiple file not supported.";
       else if(count($_FILES) == 0) $emsg = "Image is mandatory.";
       else if(!isset($_FILES["dirtyPic"])) $emsg = "Unknown file.";
       else if(strpos($_FILES["dirtyPic"]["type"], "image/") != 0) $emsg = "Unknown file type";
       else if($_FILES["dirtyPic"]["size"] > 8*1024*1024) $emsg = "Maximum upload size supported is 8MB";
       else if($_FILES["dirtyPic"]["error"] != 0) $emsg = "Error in upload (Don't forget to attach image).";
       else return false;
       $this->response->set_message($emsg);
       $this->response->set_errorcode($_FILES["dirtyPic"]["error"]);
       return true;
   }
   
   private function saveMarker() {
      if(!$this->verifyReCaptha()) {
        return;
      }
      include_once("../includes/dbinit.php");
      $Lat = mysqli_real_escape_string($conn, $_POST["Lat"]);
      $Lng = mysqli_real_escape_string($conn, $_POST["Lng"]);
      $Severe = mysqli_real_escape_string($conn, $_POST["Severe"]);
      $query = "SELECT * FROM marker_tbl WHERE Lat=".$Lat." AND Lng=".$Lng;
      $result = mysqli_query($conn, $query);
      if($result->num_rows != 0) {
          $this->response->set_message("point already marked in database");
          $this->response->set_errorcode(-1);
          return;
      }
      $src = $_FILES["dirtyPic"]["tmp_name"];
      if($this->checkForFileErrors()) {
          return;
      }
//      $ext = pathinfo($_FILES["dirtyPic"]["name"], PATHINFO_EXTENSION);
      $insert_q = "INSERT INTO marker_tbl (Lat, Lng, Severe) VALUES(".$Lat.", ".$Lng.", ".$Severe.");";
      $result = mysqli_query($conn, $insert_q);
      $dirtPicId = mysqli_insert_id($conn);
      include_once("../includes/dbclose.php");

      $dst = $this->target_dir."/".$dirtPicId;
      if (!move_uploaded_file($src, $dst)) {
          $this->response->set_message("unable to save file");
          $this->response->set_errorcode(-1);
          return;
      }
      $rsp["Lat"] = $Lat;
      $rsp["Lng"] = $Lng;
      $this->response->add_response("marker", $rsp);
   }
   private function send_response(){
      header("Content-type: application/json; charset=utf-8");
      print $this->response->to_string();
   }
}
?>
