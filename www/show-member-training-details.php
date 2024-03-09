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

                <?php include_once('includes/header.php'); ?>
                <!--//outer-wp-->
                <div class="outter-wp">
                    <!--/sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Accueil</a></li>
                            <li class="active">Détail formation</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->

                    <?php
                    $id = $_GET['id'];
                    $sql = "SELECT m.LastName as LastName, m.FirstName as FirstName, t.Type as Type, t.Active as Active, t.ID as ID, t.PaymentStatus as PaymentStatus, t.Comment as Comment                                            
                                            from myclub_member as m, myclub_trainings as t where t.ID = :id and t.memberId = m.ID";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':id', $id, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    if ($query->rowCount() > 0) {
                        foreach ($results as $row) {

                            ?>

                            <div class="graph-visual tables-main">
                                <h2 class="inner-tittle"><?php echo $utils->getTrainingLabel($row->Type); ?> de <?php echo $row->FirstName . " " . $row->LastName;?></h2>
                                <div class="graph">
                                    <div class="table-responsive">
                                        <table class="table table-striped ">
                                            <tr class="active">
                                                <td>Payement : <?php if($row->PaymentStatus == "NOTPAID") echo "A payer"; if($row->PaymentStatus == "PAID") echo "Payé"; if($row->PaymentStatus == "OTHER") echo "Autre";?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Commentaire : <?php echo $row->Comment; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Activités relatives à la formation</td>
                                            </tr>
                                            <tr class="active">
                                                <td>
                                                    <?php
                                                    $id = $_GET['id'];
                                                    $sql = "SELECT a.ActionDate as ActionDate, a.Type as Type, a.Comment as Comment, m.LastName as LastName, m.FirstName as FirstName from myclub_member as m,  myclub_training_actions as a where m.ID = a.Author and a.trainingId=:id order by a.Actiondate";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':id', $id, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) { ?>
                                                            <table class="table table-striped">
                                                                <tr class="active">
                                                                    <td><?php echo $row->FirstName . " " . $row->LastName . " le " . date("d/m/Y", strtotime($row->ActionDate)); ?></td>
                                                                    <td><?php echo $utils->getTrainingActionLabel($row->Type); ?></td>
                                                                    <td><?php echo $row->Comment; ?></td>
                                                                </tr>
                                                            </table>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <div class="new">
                                                        <a href="add-member-training-action.php?id=<?php echo $row->ID; ?>">Ajouter une activité</a>
                                                    </div>
                                                </td>
                                            </tr>

                                        </table>

                                        <div class="new">
                                            <p><a class="btn btn-default" href="manage-members-trainings.php">Retour
                                                    vers la liste des
                                                    formations</a></p>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } ?>
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