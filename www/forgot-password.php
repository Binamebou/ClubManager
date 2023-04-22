<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

if (isset($_POST['submit'])) {
    $userName = $_POST['userName'];


}


?>


<!DOCTYPE HTML>
<html>
<head>
    <?php include('includes/head.php'); ?>
</head>
<body>
<div class="page-container">
    <div class="inner-content">
        <div class="header-section">
            <div class="top_menu">
                <div class="profile_details_drop">
                    <div class="text-center"><p class="panel-title">Mot de passe oublié<br/></p></div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>

        <div class="outter-wp">
            <div class="forms-main">
                <div class="graph-form" style="width: 50%">
                    <div class="form-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="lastName">Veuillez encoder votre nom d'utilisateur</label>
                                <input id="userName" type="text" name="userName" value="" class="form-control"
                                       required='required'>
                            </div>

                            <button type="submit" class="btn btn-default" name="submit" id="submit">Demander un nouveau
                                mot
                                de passe
                            </button>
                            <input type="button" class="btn btn-warning" value="Annuler"
                                   onClick="document.location ='index.php';"/>
                        </form>
                    </div>
                </div>
            </div>
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