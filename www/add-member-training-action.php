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
        $trainingId = $_POST['trainingId'];
        $type = $_POST['type'];
        $comment = $_POST['comment'];

        $sql = "insert into myclub_training_actions(TrainingId, Type, Author, Comment)values(:TrainingId, :Type, :Author, :Comment)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':TrainingId', $trainingId, PDO::PARAM_STR);
        $query->bindParam(':Type', $type, PDO::PARAM_STR);
        $query->bindParam(':Comment', $comment, PDO::PARAM_STR);
        $query->bindParam(':Author', $_SESSION['userId'], PDO::PARAM_STR);
        $query->execute();

        $LastInsertId = $dbh->lastInsertId();
        if ($LastInsertId > 0) {
            echo "<script>window.location.href ='show-member-training-details.php?id=" . $trainingId . "'</script>";
        } else {
            echo '<script>alert("Un problème est survenu, réessayez plus tard.")</script>';
            echo "<script>window.location.href ='show-member-training-details.php?id=" . $trainingId . "'</script>";
        }

    }

    $trainingId = $_GET['id'];
    $sql = "SELECT m.LastName as LastName, m.FirstName as FirstName, t.Type as Type, t.Active as Active, t.ID as ID, t.PaymentStatus as PaymentStatus, t.Comment as Comment                                            
                                            from myclub_member as m, myclub_trainings as t 
                                            where t.ID = :id and t.memberId = m.ID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $trainingId, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    $memberName = $result->FirstName . " " . $result->LastName;
    $trainingName = $utils->getTrainingLabel($result->Type);
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
                            <li class="active">Ajouter une activité de formation</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Ajouter une activité pour la
                            formation <?php echo $trainingName . " de " . $memberName; ?></h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post" enctype="multipart/form-data">

                                    <input id="trainingId" type="hidden" name="trainingId"
                                           value="<?php echo $trainingId; ?>"/>

                                    <div class="form-group">
                                        <label for="type">Type d'action</label>
                                        <select id="type" name="type" class="form-control"
                                                required='required' style="padding: unset;">
                                            <?php
                                            $first = true;
                                            foreach ($utils->getTrainingActions() as $key => $value) {
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
                                        <label for="comment">Commentaire</label>
                                        <textarea id="comment" name="comment" cols="80" rows="10"
                                                  class="form-control"> </textarea>
                                    </div>

                                    <button type="submit" class="btn btn-default" name="submit" id="submit">
                                        Ajouter
                                    </button>


                                    <input type="button" class="btn btn-warning" value="Annuler"
                                           onClick="document.location.href=<?php echo "'show-member-training-details.php?id=" . $trainingId . "'" ?>"/>
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