<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
require_once('utils.php');

if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_INSTRUCTOR'])) {
    header('location:dashboard.php');
} else {
    $utils = new utils();

    if (isset($_POST['submit'])) {
        $trainingId = $_POST['id'];

        $sql = "update myclub_trainings set Active = 0 where ID = :trainingID";
        $query = $dbh->prepare($sql);
        $query->bindParam(':trainingID', $trainingId, PDO::PARAM_STR);
        $query->execute();
        echo "<script>window.location.href ='manage-members-trainings.php'</script>";

    }

    $trainingId = $_GET['id'];
    $sql = "SELECT m.LastName as LastName, m.FirstName as FirstName, t.Type as Type, t.Active as Active, t.ID as ID, t.PaymentStatus as PaymentStatus, t.Comment as Comment, t.TrainerId as TrainerId                                            
                                            from myclub_member as m, myclub_trainings as t 
                                            where t.ID = :id and t.memberId = m.ID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $trainingId, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    $memberName = $result->FirstName . " " . $result->LastName;
    $trainingName = $utils->getTrainingLabel($result->Type);
    $comment = $result->Comment;
    $trainerId = $result->TrainerId;
    $paymentStatus = $result->PaymentStatus;

    ?>
    <!DOCTYPE HTML>
    <html lang="fr">
    <head>
        <?php include('includes/head.php'); ?>
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
                            <li class="active">Archiver une formation</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Voulez-vous archiver la formation <?php echo $trainingName . " de " . $memberName; ?> ?</h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post" enctype="multipart/form-data">

                                    <input id="id" type="hidden" name="id"
                                           value="<?php echo $trainingId; ?>"/>

                                        <button type="submit" class="btn btn-default" name="submit" id="submit">Archiver
                                        </button>

                                    <input type="button" class="btn btn-warning" value="Annuler"
                                           onClick="document.location.href='manage-members-trainings.php'"/>
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

        $(document).ready(function () {
            $(":input").inputmask();
        }

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