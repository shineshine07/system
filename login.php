<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
// if(!isset($_SESSION['system'])){
	$system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
	foreach($system as $k => $v){
		$_SESSION['system'][$k] = $v;
	}
// }
ob_end_flush();
?>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">


    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">

    <link rel="stylesheet" type="text/css" href="assets/css/util.css">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> 

    <title>Cash Collection</title>


    <?php include('./header.php'); ?>
    <?php 
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

?>

</head>
<style>
body {
    width: 100%;
    height: calc(100%);
    position: fixed;
    top: 0;
    left: 0;
    background-image: url("assets/img/HIA3.jpg");
}

main#main {
    width: 100%;
    height: calc(120%);
    display: flex;
    border-radius:30px;
    margin-top:-40px;
    padding: 50px;
 
}
.button{
    height:25px;
}
</style>

<body>


    <main id="main">

        <div class="huhu align-self-center w-100">


            <div id="login-center" class="row justify-content-center">
                <div class="card col-md-5" style="background: maroon">
                    <div class="card-body">
                        <form id="login-form">
                            <h5 class="text-white text-center"><b></b></h5>
                            <center><div class="sidebar-brand-icon rotate-n-10">
                                <img src="assets/img/logo2.png" style="width: 150px;"></i>
                            </div></center>
                            <div class="form-group">
                                <label for="username" class="control-label text-white">Username</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
            <label for="password" class="control-label text-white">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
                <div class="input-group-append">
                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </span>
                </div>
            </div>
                            <br><br>
                            <center><button class="button btn-sm btn-block btn-wave col-md-4 btn-primary">Login</button></center>
                        </form>

                    </div>

                </div>

            </div><br>

        </div>

    </main>

    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
    $(document).ready(function () {
        $('#togglePassword').on('click', function () {
            var passwordInput = $('#password');
            var type = passwordInput.attr('type');

            // Toggle password visibility
            if (type === 'password') {
                passwordInput.attr('type', 'text');
            } else {
                passwordInput.attr('type', 'password');
            }
        });

        $('#login-form').submit(function (e) {
            e.preventDefault();
            $('#login-form button[type="button"]').attr('disabled', true).html('Logging in...');

            if ($(this).find('.alert-danger').length > 0)
                $(this).find('.alert-danger').remove();

            $.ajax({
                url: 'ajax.php?action=login',
                method: 'POST',
                data: $(this).serialize(),
                error: function (err) {
                    console.log('AJAX Error:', err);
                    $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
                },
                success: function (resp) {
                    console.log('AJAX Response:', resp);

                    if (resp == 1) {
                        location.href = 'index.php?page=home';
                    } else {
                        // Use SweetAlert for displaying the pop-up
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Username or password is incorrect!',
                        });

                        $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
                    }
                }
            });
        });
    });
</script>


</html>