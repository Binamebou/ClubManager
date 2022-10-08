<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'])) {
    header('location:dashboard.php');
} else {
    $id = $_GET['id'];
    $sql = "select * from myclub_member where ID=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $member = $query->fetch(PDO::FETCH_OBJ);

    if (isset($_POST['submit'])) {
        $years = $_POST['year'];
        $membershipType = $_POST['membershipType'];
        for ($i = 0; $i < count($years); $i++) {
            if ($membershipType[$i] == "Remove") {
                $sql = "delete from myclub_membership where MemberId=:id and Year = :year";
                $query = $dbh->prepare($sql);
                $query->bindParam(':id', $id, PDO::PARAM_STR);
                $query->bindParam(':year', $years[$i]);
                $query->execute();
            } else {
                $sql = "update myclub_membership set Type=:type where MemberId=:id and Year = :year";
                $query = $dbh->prepare($sql);
                $query->bindParam(':id', $id, PDO::PARAM_STR);
                $query->bindParam(':year', $years[$i]);
                $query->bindParam(':type', $membershipType[$i]);
                $query->execute();
            }
        }
        echo '<script>alert("Les cotisations ont été mises à jour")</script>';
        echo "<script type='text/javascript'> document.location ='manage-membership.php'; </script>";
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

                <?php include_once('includes/header.php'); ?>
                <!--//outer-wp-->
                <div class="outter-wp">
                    <!--/sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Accueil</a></li>
                            <li class="active">Edition cotisations</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Edition des cotisations
                            de <?php echo $member->FirstName . " " . $member->LastName ?></h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post">
                                    <?php
                                    $sql = "SELECT * from myclub_membership where MemberId=:id order by Year DESC";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':id', $id, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $row) { ?>
                                            <div class="form-group form-inline">
                                                <input type="text" name="year[]" value="<?php echo $row->Year; ?>"
                                                       class="form-control" readonly="readonly">
                                                <select id="membershipType" name="membershipType[]"
                                                        required='true' class="form-control form-control-sm"
                                                        style="padding: unset;">
                                                    <option value="Remove">Annuler la cotisation</option>
                                                    <option value="Annuelle" <?php if ($row->Type == "Annuelle") echo 'selected="selected"'; ?>>
                                                        Année complète
                                                    </option>
                                                    <option value="Septembre" <?php if ($row->Type == "Septembre") echo 'selected="selected"'; ?>>
                                                        A partir de septembre
                                                    </option>
                                                </select>
                                            </div>
                                            <?php $cnt = $cnt + 1;
                                        }
                                    } ?>
                                    <button type="submit" class="btn btn-default" name="submit" id="submit">Mettre à
                                        jour
                                    </button>
                                    <input type="button" class="btn btn-warning" value="Annuler"
                                           onClick="document.location.href='manage-membership.php'"/>
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