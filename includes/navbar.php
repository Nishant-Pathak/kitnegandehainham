<?php
    $path = substr($_SERVER['REQUEST_URI'],1);
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
              Logged in as <a href="#" class="navbar-link">Guest</a>
            </p>
            <ul class="nav navbar-nav">
              <li <?php if($path == "") echo "class='active'"; ?> ><a href="/">Home</a></li>
              <li <?php if($path == "view.php") echo "class='active'"; ?> ><a href="/view.php">View</a></li>
              <li <?php if($path == "about.php") echo "class='active'"; ?> ><a href="/about.php">About</a></li>
              <li <?php if($path == "contact.php") echo "class='active'"; ?> ><a href="/contact.php">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
