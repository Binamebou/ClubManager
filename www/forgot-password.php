<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
?>

<!DOCTYPE HTML>
<html>
<head>
    <?php include('includes/head.php'); ?>
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