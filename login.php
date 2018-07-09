<?php
include 'connection.php';
// include 'controller/Login.php';
// createGuru($connect);
// createOrtu($connect);
if (isset($_SESSION['logged_id']) ) {
  header("location: /index.php");
}

$message = "";
$oldUsername = "";
if (isset($_POST['login'])) {
    $_SESSION['oldUsername'] = $_POST['username'];
    $oldUsername = $_POST['username'];
    $query = "SELECT * FROM tb_admin WHERE username = :username";
    $statement = $connect->prepare($query);
    $statement->execute([
        'username' => $_POST['username']
    ]);
    $count = $statement->rowCount();
    if ($count > 0) {
        $result = $statement->fetchAll();
        foreach ($result as $row) {
            if ($row['status'] == 'active') {
                if (password_verify($_POST['password'], $row['password'])) {
                    $_SESSION['logged_id'] = true;
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['jabatan'] = $row['jabatan'];
                    $_SESSION['status'] = $row['status'];
                    $_SESSION['avatar'] = $row['avatar'];

                    header("location: index.php");
                }else {
                    $message = "<label class='text-danger'>Password salah!</label>";
                }
            }else {
                $message = "<label class='text-danger'>Akun anda telah di nonaktifkan</label>";
            }
        }
    }else {
        $message = "<label class='text-danger'>Username salah!</label>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>LOGIN</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="assets/dist/css/skins/_all-skins.min.css">
  <link rel="icon" href="favicon.ico">
  <style type="text/css">
    body {
      height: auto;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="javascript:void(0)"><b>Login</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <div class="image">
      <div class="row">
        <div class="col-md-12">
          <img style="width: 100%; margin-bottom: 20px; " src="assets/img/AVUI.jpg">
        </div>
      </div>
    </div>
    <form method="post">
      <?php echo $message; ?>
      <div class="form-group has-feedback">
        <input type="text" name="username" required class="form-control" placeholder="Username" >
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group" style="padding-bottom: 30px;">
          <div class="col-xs-8">
              <div class="checkbox icheck">
                  <label>
                      <input type="checkbox"> Remember Me
                  </label>
              </div>
          </div>
          <div class="col-xs-4">
              <button type="submit" name="login" class="btn btn-primary btn-block btn-flat">Sign In</button>
          </div>
      </div>
    </form>
  </div>
</div>
<!-- jQuery 3 -->
<script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>
