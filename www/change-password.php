<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
error_reporting(0);
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $userid = $_SESSION['userId'];
        $cpassword = md5($_POST['currentpassword']);
        $newpassword = md5($_POST['newpassword']);
        $sql = "SELECT ID FROM myclub_member WHERE ID=:userid and Password=:cpassword";
        $query = $dbh->prepare($sql);
        $query->bindParam(':userid', $userid, PDO::PARAM_STR);
        $query->bindParam(':cpassword', $cpassword, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            $con = "update myclub_member set Password=:newpassword where ID=:userid";
            $chngpwd1 = $dbh->prepare($con);
            $chngpwd1->bindParam(':userid', $userid, PDO::PARAM_STR);
            $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $chngpwd1->execute();

            echo '<script>alert("Votre mot de passe a été changé avec succès")</script>';
            echo "<script>window.location.href ='change-password.php'</script>";
        } else {
            echo '<script>alert("Votre mot de passe actuel est incorrect")</script>';

        }
    }
    ?>
    <!DOCTYPE HTML>
    <html>
    <head>
        <title><?php echo $siteName; ?></title>

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
        <script src="js/css3clock.js"></script>
        <!--Easy Pie Chart-->
        <!--skycons-icons-->
        <script src="js/skycons.js"></script>
        <!--//skycons-icons-->
        <script type="text/javascript">
            function checkpass() {
                if (document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
                    alert('La confirmation de votre mot de passe n\'est pas identique au mot de passe choisi');
                    document.changepassword.confirmpassword.focus();
                    return false;
                }
                return true;
            }

        </script>
    </head>
    <body>
    <div class="page-container">
        <!--/content-inner-->
        <div class="left-content">
            <div class="inner-content">

                <?php include_once('includes/header.php'); ?>
                <!--//outer-wp-->
                <div class="outter-wp">
                    <!--/sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Accueil</a></li>
                            <li class="active">Paramètres</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Changer votre mot de passe </h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form name="changepassword" method="post" onsubmit="return checkpass();" action="">

                                    <div class="form-group"><label for="exampleInputEmail1">Mot de passe actuel</label>
                                        <input type="password" name="currentpassword" id="currentpassword"
                                               class="form-control" required="true"></div>
                                    <div class="form-group"><label for="exampleInputEmail1">Nouveau mot de passe</label>
                                        <input type="password" name="newpassword" class="form-control" required="true">
                                    </div>
                                    <div class="form-group"><label for="exampleInputEmail1">Confirmez votre nouveau mot
                                            de passe</label><input type="password" name="confirmpassword"
                                                                   id="confirmpassword" value="" class="form-control"
                                                                   required="true"></div>

                                    <button type="submit" class="btn btn-default" name="submit" id="submit">Mettre à
                                        jour
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
        <?php include_once('includes/sidebar.php'); ?>
        <div class="clearfix"></div>
    </div>
    <script>
        var toggle = true;

        $(".sidebar-icon").click(function () {
            if (toggle) {
                $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                $("#menu span").css({"position": "absolute"});
            } else {
                $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                setTimeout(function () {
                    $("#menu span").css({"position": "relative"});
                }, 400);
            }

            toggle = !toggle;
        });
    </script>
    <!--js -->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    </body>
    </html>
<?php } ?>