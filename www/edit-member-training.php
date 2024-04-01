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
        $type = $_POST['type'];
        $newTrainerId = $_POST['newTrainerId'];
        $paymentStatus = $_POST['paymentStatus'];
        $comment = $_POST['comment'];

        $sql = "update myclub_trainings set TrainerId = :trainerId, PaymentStatus = :paymentStatus, Comment = :comment where ID = :trainingID";
        $query = $dbh->prepare($sql);
        $query->bindParam(':trainingID', $trainingId, PDO::PARAM_STR);
        $query->bindParam(':trainerId', $newTrainerId, PDO::PARAM_STR);
        $query->bindParam(':paymentStatus', $paymentStatus, PDO::PARAM_STR);
        $query->bindParam(':comment', $comment, PDO::PARAM_STR);
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

    $sqlTrainer = "SELECT * from myclub_member as m, myclub_rights as r where r.member_id = m.ID AND r.role_id = 'INSTRUCTOR' AND m.active = 1 order by LastName, FirstName";
    $queryTrainer = $dbh->prepare($sqlTrainer);
    $queryTrainer->execute();
    $trainers = $queryTrainer->fetchAll(PDO::FETCH_OBJ);

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
                            <li class="active">Editer une formation</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Editer la formation <?php echo $trainingName . " de " . $memberName; ?></h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post" enctype="multipart/form-data">

                                    <input id="id" type="hidden" name="id"
                                           value="<?php echo $trainingId; ?>"/>

                                        <div class="form-group">
                                            <label for="newTrainerId">Moniteur responsable</label>
                                            <select id="newTrainerId" name="newTrainerId" class="form-control"
                                                    required='required' style="padding: unset;">
                                                <option value="">--Sélectionnez un moniteur--</option>
                                                <?php
                                                if ($queryTrainer->rowCount() > 0) {
                                                    foreach ($trainers as $row) {
                                                        echo '<option value="' . $row->ID . '" ';
                                                        if ($trainerId == $row->ID) { echo 'selected = "true"';}
                                                        echo '>' . $row->LastName . " " . $row->FirstName . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="paymentStatus">Paiement</label>
                                            <select id="paymentStatus" name="paymentStatus"
                                                    required='required' class="form-control" style="padding: unset;">
                                                <option value="NOTPAID" <?php if ($paymentStatus == "NOTPAID") { echo 'selected = "true"';} ?>>Non payé</option>
                                                <option value="PAID" <?php if ($paymentStatus == "PAID") { echo 'selected = "true"';} ?>>Payé</option>
                                                <option value="OTHER" <?php if ($paymentStatus == "OTHER") { echo 'selected = "true"';} ?>>Autre</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="comment">Commentaire</label>
                                            <textarea id="comment" name="comment" cols="80" rows="10"
                                                      class="form-control"><?php echo htmlspecialchars($comment); ?></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-default" name="submit" id="submit">Mettre à jour
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