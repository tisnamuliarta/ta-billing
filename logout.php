<?php
include ('connection.php');
if($_GET['logout'] == 1) {
    session_start();
    session_destroy();
    header("location: index.php");
}else {
    header("location: index.php");
}