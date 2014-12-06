<?php
    $path = substr($_SERVER['REQUEST_URI'],1);
    session_start();
?>
      <!-- Fixed navbar -->
      <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Kitne Gande Hain Ham</a>
          </div>
          <div class="collapse navbar-collapse">
            <p class="navbar-text pull-right">
              Logged in as <a href="#" class="navbar-link"><?php echo isset($_SESSION["user"])? $_SESSION["user"]: "Guest"; ?></a>
            </p>
            <ul class="nav navbar-nav">
              <li <?php if($path == "home.php") echo "class='active'"; ?> ><a href="/home.php">Home</a></li>
              <li <?php if($path == "plot.php") echo "class='active'"; ?> ><a href="/">Plot</a></li>
              <li <?php if($path == "view.php") echo "class='active'"; ?> ><a href="/view.php">View</a></li>
              <li <?php if($path == "contact.php") echo "class='active'"; ?> ><a href="/contact.php">Contact</a></li>
              <?php if(isset($_SESSION["user"])) { ?>
              <li <?php if($path == "verify.php") echo "class='active'"; ?> ><a href="/verify.php">Verify</a></li>
              <?php } ?>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
