<?php
    session_start();
    $title = "Absensi Karyawan | Login";
    require 'template/header.php';

    if (!empty($_SESSION['status']) && $_SESSION['status'] === "online") {
        header("location:dashboard.php");
        exit;
    }
?>
<style>
    .navbar {
        padding: 0.5pc 0.5pc 0.5pc 1.5pc;
        border-style: none none solid none;
        border-color:#fdb606;
        border-width:5px;
    } 
    .card{ border:0; box-shadow:3px 3px 10px -5px; }
    .container{ margin-top:70px; }
    .banner{ width:80%; }
</style>

<nav class="navbar navbar-dark bg-dark">
    <a href="#" class="navbar-brand">
        <h3>PERCETAKAN FARHAN ABADI</h3>
        <small>Aplikasi Absensi Karyawan Berbasis WEB</small>
    </a>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-7">
            <img class="banner" src="img/bg-login.jpg" alt="Ilustrasi Login">
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <center><h4>Sign In</h4></center>
                    <hr>
                    <center>
                        <div id="data" style="min-height:20px;"></div>
                        <hr>
                    </center>

                    <form action="proses/cek_login.php" method="POST" autocomplete="on" novalidate>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input id="username" name="username" type="text" class="form-control"
                                   placeholder="Username Anda" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label> 
                            <input id="password" name="password" type="password" class="form-control"
                                   placeholder="Password Anda" required>
                        </div>

                        <hr>
                        <?php require 'alert.php'; ?>

                        <button type="submit" name="login" class="btn btn-primary btn-block">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'template/footer.php'; ?>

<script type="text/javascript">
    $(function () {
        function pullStatus() {
            $("#data").load('get_otomatis.php', function (response, status, xhr) {
                if (status === "error") {
                }
            });
        }
        pullStatus(); 
        setInterval(pullStatus, 1000); 
    });
</script>
