<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <?php include('includes/head.php'); ?>
    <script src="js/amcharts.js"></script>
    <script src="js/serial.js"></script>
    <script src="js/light.js"></script>
    <script src="js/radar.js"></script>
    <link href="css/barChart.css" rel='stylesheet' type='text/css'/>
    <link href="css/fabochart.css" rel='stylesheet' type='text/css'/>
    <!--clock init-->
    <script src="js/css3clock.js"></script>
    <!--Easy Pie Chart-->
    <!--skycons-icons-->
    <script src="js/skycons.js"></script>

    <script src="js/jquery.easydropdown.js"></script>

    <!--//skycons-icons-->
</head>
<body>
<div class="page-container">
    <!--/content-inner-->
    <div class="left-content">
        <div class="inner-content">

            <?php include_once('includes/header.php'); ?>

            <div class="outter-wp">
                <!--custom-widgets-->
                <div class="custom-widgets">
                    <div class="row-one">
                        <?php
                        if ($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER']) { ?>
                            <div class="col-md-4 widget">
                                <div class="stats-left ">
                                    <?php
                                    $sql = "SELECT ID from myclub_member";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $membersCount = $query->rowCount();
                                    ?>
                                    <h5>Nombre de membres dans le système</h5>
                                </div>
                                <div class="stats-right">
                                    <span><?php echo htmlentities($membersCount); ?></span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="col-md-4 widget">
                                <div class="stats-left ">
                                    <?php
                                    $year = Date("Y");
                                    $sql = "SELECT ID from myclub_membership where myclub_membership.Year = :year";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':year', $year);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $membersCount = $query->rowCount();
                                    ?>
                                    <h5>En ordre de cotisation pour l'année <?php echo $year; ?></h5>
                                </div>
                                <div class="stats-right">
                                    <span><?php echo htmlentities($membersCount); ?></span>
                                </div>
                                <div class="clearfix"></div>
                            </div> <?php
                        } ?>
                    </div>
                </div>
            </div>
            <div class="outter-wp">
                <div class="custom-widgets">
                    <div class="row-one">
                        <div class="col-md-4 widget">
                            <div class="stats-left ">
                                <?php
                                $sql = "SELECT * from myclub_certificates where MemberId = :id";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':id', $_SESSION['userId'], PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $certificatesCount = $query->rowCount();
                                ?>
                                <h5>Nombre de vos brevets encodés dans le système</h5>
                            </div>
                            <div class="stats-right">
                                <span><?php echo htmlentities($certificatesCount); ?></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="outter-wp">
                <!--custom-widgets-->

                <?php include_once('includes/footer.php'); ?>

            </div>
        </div>
        <!--//content-inner-->

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
    <link rel="stylesheet" href="css/vroom.css">
    <script type="text/javascript" src="js/vroom.js"></script>
    <script type="text/javascript" src="js/TweenLite.min.js"></script>
    <script type="text/javascript" src="js/CSSPlugin.min.js"></script>
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>