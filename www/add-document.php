<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else {

    if (isset($_POST['submit'])) {
        $memberId = $_SESSION['userId'];

        $type = $_POST['type'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $comment = $_POST['comment'];

        $target_dir = "../documents/".$_SESSION['lastName']." ".$_SESSION['firstName']."/".$type."/";
        mkdir($target_dir, 0755, true);
        $target_file = $target_dir . uniqid() . "." . pathinfo(basename($_FILES["file"]["name"]),PATHINFO_EXTENSION);

        if ($_FILES["file"]["size"] > 2000000) {
            echo '<script>alert("Le fichier fait plus de 2Mb.")</script>';
        } else if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

            $sql = "insert into myclub_documents(MemberId, Type, ValidFrom, ValidTo, Path, Comment)values(:MemberId,:Type,:ValidFrom,:ValidTo,:Path,:Comment)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':MemberId', $memberId, PDO::PARAM_STR);
            $query->bindParam(':Type', $type, PDO::PARAM_STR);
            $query->bindParam(':ValidFrom', $from, PDO::PARAM_STR);
            $query->bindParam(':ValidTo', $to, PDO::PARAM_STR);
            $query->bindParam(':Path', $target_file, PDO::PARAM_STR);
            $query->bindParam(':Comment', $comment, PDO::PARAM_STR);
            $query->execute();

            echo "<script>window.location.href ='my-documents.php'</script>";

        } else {
            echo '<script>alert("Un problème est survenu, réessayez plus tard.")</script>';
        }

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
                            <li class="active">Ajouter un document</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Ajouter un document</h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label for="type">Type</label>
                                        <select id="type" name="type" class="form-control"
                                               required='required' style="padding: unset;">
                                            <option value="Assurance DAN">Assurance DAN</option>
                                            <option value="Certificat Médical">Certificat Médical</option>
                                            <option value="Certificat ORL">Certificat ORL</option>
                                            <option value="Certificat ECG">Certificat ECG</option>
                                            <option value="Autre">Autre</option>
                                        </select>

                                    </div>
                                    <div class="form-group">
                                        <label for="from">Valide à partir du</label>
                                        <input id="from" type="date" name="from" value="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="to">Valide jusqu'au</label>
                                        <input id="to" type="date" name="to" value="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="comment">Commentaire</label>
                                        <input id="comment" type="text" name="comment" value="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="file">Fichier (max 2Mb)</label>
                                        <input type="file" name="file" required="required" class="form-control-file" style="padding: unset;">
                                    </div>

                                    <button type="submit" class="btn btn-default" name="submit" id="submit">Ajouter
                                    </button>
                                    <input type="button" class="btn btn-warning" value="Annuler"
                                           onClick="document.location.href='my-documents.php'"/>
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