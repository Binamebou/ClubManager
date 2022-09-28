<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
?>

<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $siteName;?></title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">

    <script type="application/x-javascript"> addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        } </script>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css'/>
    <!-- Custom CSS -->
    <link href="css/style.css" rel='stylesheet' type='text/css'/>
    <!-- Graph CSS -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- jQuery -->
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet'
          type='text/css'>
    <!-- lined-icons -->
    <link rel="stylesheet" href="css/icon-font.min.css" type='text/css'/>
    <!-- //lined-icons -->
    <script src="js/jquery-1.10.2.min.js"></script>
    <!--clock init-->

</head>
<body>
<div class="error_page">

    <div class="error-top">
        <h2 class="inner-tittle page"><?php echo $siteName;?></h2>
        <div class="login">

            <div class="buttons login">
                <h3 class="inner-tittle t-inner" style="color: lightblue">Veuillez contacter le secrétaire du club</h3>
            </div>
            <div class="new">
                <p><a href="/index.php">Retour vers l'accueil</a></p>
                <div class="clearfix"></div>
            </div>
        </div>


    </div>


    <!--//login-top-->
</div>

<!--//login-->
<!--footer section start-->
<div class="footer">

    <?php include_once('includes/footer.php'); ?>
</div>
<!--footer section end-->
<!--/404-->
<!--js -->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>