<?php  

if (!isset($_SESSION['logged_id']) ) {
    header("location: /login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Billing</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php __DIR__ ?>/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php __DIR__ ?>/assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php __DIR__ ?>/assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php __DIR__ ?>/assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php __DIR__ ?>/assets/dist/css/skins/_all-skins.min.css">

  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="hold-transition skin-blue layout-top-nav">
  <div class="wrapper">
    <?php 

    require_once 'header.php';

    ?>

    <div class="content-wrapper">
      <section class="content">
