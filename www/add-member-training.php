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
        $memberId = $_POST['memberId'];
        $memberName = $_POST['memberName'];
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $type = $_POST['type'];
        $trainerId = $_POST['trainerId'];
        $paymentStatus = $_POST['paymentStatus'];
        $comment = $_POST['comment'];

        $sql = "insert into myclub_trainings(MemberId, Type, TrainerId, PaymentStatus, Comment)values(:MemberId,:Type,:TrainerId,:PaymentStatus, :Comment)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':MemberId', $memberId, PDO::PARAM_STR);
        $query->bindParam(':Type', $type, PDO::PARAM_STR);
        $query->bindParam(':TrainerId', $trainerId, PDO::PARAM_STR);
        $query->bindParam(':PaymentStatus', $paymentStatus, PDO::PARAM_STR);
        $query->bindParam(':Comment', $comment, PDO::PARAM_STR);
        $query->execute();

        $LastInsertId = $dbh->lastInsertId();
        if ($LastInsertId > 0) {

            $sql = "insert into myclub_training_actions(TrainingId, Type, Author)values(:TrainingId,'CREATED',:Author)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':TrainingId', $LastInsertId, PDO::PARAM_STR);
            $query->bindParam(':Author', $_SESSION['userId'], PDO::PARAM_STR);
            $query->execute();

            echo "<script>window.location.href ='manage-members-trainings.php'</script>";
        } else {
            echo '<script>alert("Un problème est survenu, réessayez plus tard.")</script>';
        }

    } else if (isset($_POST['submitMember'])) {
        $memberId = $_POST['idOption'];
        $sql = "SELECT * from myclub_member where ID=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $memberId, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() > 0) {
            foreach ($results as $row) {
                $memberName = $row->FirstName . " " . $row->LastName;
                $lastName = $row->LastName;
                $firstName = $row->FirstName;
            }
        }

        $sqlTrainer = "SELECT * from myclub_member as m, myclub_rights as r where r.member_id = m.ID AND r.role_id = 'INSTRUCTOR' AND m.active = 1 order by LastName, FirstName";
        $queryTrainer = $dbh->prepare($sqlTrainer);
        $queryTrainer->execute();
        $trainers = $queryTrainer->fetchAll(PDO::FETCH_OBJ);
    }

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
                            <li class="active">Ajouter une formation à un membre</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Ajouter une formation <?php if ($memberName) {
                                echo "à " . $memberName;
                            } ?></h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post" enctype="multipart/form-data">

                                    <input id="memberId" type="hidden" name="memberId"
                                           value="<?php echo $memberId; ?>"/>
                                    <input id="memberName" type="hidden" name="memberName"
                                           value="<?php echo $memberName; ?>"/>
                                    <input id="lastName" type="hidden" name="lastName"
                                           value="<?php echo $lastName; ?>"/>
                                    <input id="firstName" type="hidden" name="firstName"
                                           value="<?php echo $firstName; ?>"/>
                                    <?php
                                    if (!$memberId) { ?>

                                        <div class="form-group">
                                            <label for="idOption">Membre concerné</label>
                                            <select id="idOption" name="idOption"
                                                    required='required'>
                                                <option value="">--Sélectionnez un élève--</option>
                                                <?php
                                                $sql = "SELECT * from myclub_member where active = 1 order by LastName, FirstName";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                if ($query->rowCount() > 0) {
                                                    foreach ($results as $row) {
                                                        echo '<option value="' . $row->ID . '">' . $row->LastName . " " . $row->FirstName . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-default" name="submitMember"
                                                id="submitMember">
                                            Sélectionner cet élève
                                        </button>

                                    <?php } else {
                                        ?>

                                        <div class="form-group">
                                            <label for="type">Type de formation</label>
                                            <select id="type" name="type" class="form-control"
                                                    required='required' style="padding: unset;">
                                                <?php
                                                $first = true;
                                                foreach ($utils->getTrainings() as $key => $value) {
                                                    echo '<option ';
                                                    if ($first) {
                                                        echo 'selected="true" ';
                                                    }
                                                    $first = false;
                                                    echo "value=\"$key\">$value</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="trainerId">Moniteur responsable</label>
                                            <select id="trainerId" name="trainerId" class="form-control"
                                                    required='required' style="padding: unset;">
                                                <option value="">--Sélectionnez un moniteur--</option>
                                                <?php
                                                if ($queryTrainer->rowCount() > 0) {
                                                    foreach ($trainers as $row) {
                                                        echo '<option value="' . $row->ID . '">' . $row->LastName . " " . $row->FirstName . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="paymentStatus">Paiement</label>
                                            <select id="paymentStatus" name="paymentStatus"
                                                    required='required' class="form-control" style="padding: unset;">
                                                <option value="NO" selected="selected">Non payé</option>
                                                <option value="NO">Payé</option>
                                                <option value="NO">Offert</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="comment">Commentaire</label>
                                            <textarea id="comment" name="comment" cols="80" rows="10"
                                                      class="form-control"> </textarea>
                                        </div>

                                        <button type="submit" class="btn btn-default" name="submit" id="submit">Ajouter
                                        </button>

                                        <?php
                                    }
                                    ?>

                                    <input type="button" class="btn btn-warning" value="Annuler"
                                           onClick="document.location.href='dashboard.php'"/>
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