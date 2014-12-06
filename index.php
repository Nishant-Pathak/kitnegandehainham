<?php
    $path = substr($_SERVER['REQUEST_URI'],1);
    session_start();
    if(isset($_COOKIE["ft"])) {
        header("Location:/plot.php");
    } else {
        setcookie("ft",'1',time()+604800 ,'/'); //for one week
        header("Location:/home.php");
    }
?>