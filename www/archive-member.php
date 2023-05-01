<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'])) {
    header('location:dashboard.php');
} else {
    if (isset($_POST['submit'])) {
        $id = $_GET['id'];

        $sql = "delete from myclub_rights where member_id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();

        $sql = "update myclub_member set active = 0, LastUpdate = CURDATE() where ID=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        echo '<script>alert("Le membre a été archivé, vous pouvez encore le restaurer ou le supprimer définitivement via la liste des membres.")</script>';
        echo "<script type='text/javascript'> document.location ='manage-members.php'; </script>";
    }
    ?>
    <!DOCTYPE HTML>
    <html>
    <head>
        <?php include('includes/head.php'); ?>
    </head>
    <body>
    <div class="page-container">
        <!--/content-inner-->
        <div class="left-content">
            <div class="inner-content">

                <?php include_once('includes/header.php');?>
                <!--//outer-wp-->
                <div class="outter-wp">
                    <!--/sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Accueil</a></li>
                            <li class="active">Archivage membre</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Archivage d'un membre</h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post">
                                    <?php
                                    $id = $_GET['id'];
                                    $sql = "SELECT * from myclub_member where ID=:id";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':id', $id, PDO::PARAM_STR);
                                    $query->execute();
                                    $member = $query->fetch(PDO::FETCH_OBJ);

                                    if ($member) { ?>
                                        <h2 class="inner-tittle">Etes vous certain de vouloir archiver le membre <?php  echo $member->FirstName . " " . $member->LastName;?> ?</h2>
                                        <button type="submit" class="btn btn-default" name="submit" id="submit">Archiver</button>
                                    <?php  } ?>

                                    <input type="button" class="btn btn-warning" value="Annuler" onClick="document.location.href='manage-members.php'" />
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