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
        $memberId = $_POST['memberId'];
        $memberName = $_POST['memberName'];
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $type = $_POST['type'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $comment = $_POST['comment'];

        $target_dir = "../documents/" . $lastName . " " . $firstName . "/" . $type . "/";
        mkdir($target_dir, 0755, true);
        $target_file = $target_dir . uniqid() . "." . pathinfo(basename($_FILES["file"]["name"]), PATHINFO_EXTENSION);

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
                            <li class="active">Ajouter un document à un membre</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Ajouter un document <?php if ($memberName) {
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
                                                $sql = "SELECT * from myclub_member order by LastName, FirstName";
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
                                            <input id="comment" type="text" name="comment" value=""
                                                   class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="file">Fichier (max 2Mb)</label>
                                            <input type="file" name="file" required="required" class="form-control-file"
                                                   style="padding: unset;">
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