<?php
include_once("includes/header.php");
include_once("includes/navbar.php");

?>
      <!-- Begin page content -->
      <div class="container">
        <div class="page-header">
          <h3>Administrator Login:</h3>
        </div>
        <div class="col-sm-2">
        <form role="form" method="POST" action="nitro/nitro.php?action=login">
            <div class="form-group">
                <label for="UserName">User Name</label>
                <input type="text" class="form-control" id="UserName" name="UserName" placeholder="User Name">
            </div>
            <div class="form-group">
                <label for="Password">Password</label>
                <input type="password" class="form-control" id="Password" name="Password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
        </div>
      </div>
    </div>

<?php

include_once("includes/footer.php");
?>
