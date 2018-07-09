<?php
include ('connection.php');
?>

<?php

if (!isset($_SESSION['logged_id']) ) {
    header("location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Billing</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
	<!-- daterange picker -->
	<link rel="stylesheet" href="assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
	<!-- bootstrap datepicker -->
	<link rel="stylesheet" href="assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
	   folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="assets/dist/css/skins/_all-skins.min.css">

	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="hold-transition skin-blue layout-top-nav">
  <div class="wrapper">
    <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="index.php" class="navbar-brand"><b>Billing</b></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <?php if ($_SESSION['jabatan'] == 'admin'): ?>
                <li><a href="page/admin.php">Admin</a></li>
                <li><a href="page/report.php">Report</a></li>
            <?php endif; ?>
            <li><a href="page/customer.php">Customer</a></li>
            <li><a href="page/transaksi.php">Transaksi</a></li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Notifications Menu -->
            
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="assets/img/<?= $_SESSION['avatar'] ?>" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs"><?php echo $_SESSION['username'] ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="assets/img/<?= $_SESSION['avatar'] ?>" class="img-circle" alt="User Image">

                  <p>
                    <?php echo $_SESSION['username'] ?> - <?= $_SESSION['jabatan'] ?>
                  </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-right">
                    <a href="/logout.php?logout=1" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>


    <div class="content-wrapper">
      <section class="content">
	<div class="container">
	    <section class="content">
	        <div class="callout callout-info">
	          <h3>Selamat Datang!</h3>
	          <hr>
	          <p>Selamat datang di aplikasi billing.</p>
	        </div>
	      </section>
	</div>

	</section>
		</div>
		<footer class="main-footer">
		    <strong>Copyright &copy; 2018 <a href="javascript:void(0)">Billing System</a>.</strong> All rights
		    reserved.
		</footer>
	</div>
	<!-- jQuery 3 -->
	<script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- SlimScroll -->
	<script src="assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<!-- FastClick -->
	<script src="assets/bower_components/fastclick/lib/fastclick.js"></script>
	<!-- date-range-picker -->
	<script src="assets/bower_components/moment/min/moment.min.js"></script>
	<script src="assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
	<!-- bootstrap datepicker -->
	<script src="assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<!-- AdminLTE App -->
	<script src="assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
	<script src="assets/dist/js/adminlte.min.js"></script>
	<script src="assets/js/app.js"></script>
	<script src="assets/js/index.js"></script>
	<script src="assets/js/jsCookie.js"></script>
	<script type="text/javascript">
		$(function(){
			var url = window.location.pathname;
			$('ul.navbar-nav li a[href="' + url + '"]').parent().addClass('active');
		    $('li#link-sidebar a[href="' + url + '"]').parent().addClass('active');

			//Date picker
			$('#datepicker').datepicker({
			  	autoclose: true,
				format: 'mm-dd-yyyy'
			})

		})
	</script>
</body>
</html>




