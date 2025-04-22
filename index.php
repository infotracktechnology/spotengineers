<?php
ob_start();
session_start();
$_SESSION['cyear']='2025-2026';
?>
<!DOCTYPE html>
<html lang="en">


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Spot Engineers</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/bundles/bootstrap-social/bootstrap-social.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
</head>
<style type="text/css">
</style>

<body>
  <div class="loader"></div>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-lg-8 offset-lg-2">
            <div class="card card-primary">
              <div class="card-header">

                <h3>Login</h3>

              </div>
              <div class="card-body">

                <?php if (isset($_SESSION['error'])) { ?>
                  <div class="small alert alert-danger">
                    <?php
                    echo $_SESSION['error'];
                    ?>
                  </div> <?php
                          unset($_SESSION['error']);
                        }
                          ?>

                <form method="post" action="./api/login.php" enctype="multipart/form-data" class="needs-validation" novalidate="">

                  <div class="row">

                    <div class="col-12 col-lg-6">
                      <div class="form-group col-12 mb-4">
                        <label for="email">Username</label>
                        <input type="text" class="form-control form-control-sm" name="username" placeholder="Username" required="" autofocus>
                        <div class="invalid-feedback">
                          Please fill in your Username
                        </div>
                      </div>
                      <div class="form-group col-12 mb-4">
                        <!-- <div class="d-block">
                          <label for="password" class="control-label">Password</label>
                          <div class="float-right">
                            <a href="auth-forgot-password.html" class="text-small">
                              Forgot Password?
                            </a>
                          </div>

                        </div> -->
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" placeholder="Password" required="" class="form-control form-control-sm">
                        <div class="invalid-feedback">
                          please fill in your password
                        </div>
                      </div>
                      <div class="form-group col-12 mb-4">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                          <label class="custom-control-label" for="remember-me">Remember Me</label>
                        </div>
                      </div>
                      <div class="form-group col-12 mb-4">
                        <button type="submit" class="btn bg-purple btn-lg btn-block text-white" tabindex="4">
                          Login
                        </button>
                      </div>
                    </div>

                    <div class="col-lg-6 col-12 d-md-block d-none text-center align-self-center p-3">

                      <img class="img-fluid" src="https://www.linelogictech.com/icode/app-assets/images/pages/login.png" alt="branding logo">

                    </div>

                  </div>
                </form>

              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
    <center><a class="txt2" href="http://www.infotrackin.com/" target="_blank">
        ©<?php echo date('Y'); ?> All Rights Reserved By <b style="color: #27a9e0">Infotrack Technology Solution</b>
        <i class="fa fa-mail-forward" aria-hidden="true" style="color: #f05a40"></i>
      </a></center>
  </div>
  <!-- General JS Scripts -->
  <script src="assets/js/app.min.js"></script>
  <!-- JS Libraies -->
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <!-- Custom JS File -->
  <script src="assets/js/custom.js"></script>
</body>


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->

</html>