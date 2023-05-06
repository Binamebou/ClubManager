<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'])) {
    header('location:dashboard.php');
} else {

    if ($_POST['year']) {
        $year = $_POST['year'];
    } else {
        $year = date("Y");
    }

    if ($_POST['idMember']) {
        try {
            $sql = "INSERT INTO myclub_membership (MemberId, Year, Type)  values (:memberId, :year, :type)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':memberId', $_POST['idMember'], PDO::PARAM_STR);
            $query->bindParam(':year', $year);
            $query->bindParam(':type', $_POST['membershipType'], PDO::PARAM_STR);
            $success = $query->execute();
        } catch
        (PDOException $e) {
            if ($e->getCode() == 23000) {
                $sql = "UPDATE myclub_membership set Type = :type where MemberId = :memberId and Year = :year";
                $query = $dbh->prepare($sql);
                $query->bindParam(':memberId', $_POST['idMember'], PDO::PARAM_STR);
                $query->bindParam(':year', $year);
                $query->bindParam(':type', $_POST['membershipType'], PDO::PARAM_STR);
                $success = $query->execute();
            } else {
                echo '<script>alert("Un problème de mise à jour est survenu : '.$e->getMessage().'")</script>';
                echo "<script type='text/javascript'> document.location ='manage-membership.php'; </script>";
            }
        }
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
                <!-- header-starts -->
                <?php include_once('includes/header.php'); ?>
                <!-- //header-ends -->
                <!--outter-wp-->
                <div class="outter-wp">
                    <!--sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Accueil</a></li>
                            <li class="active">Cotisations</li>
                        </ol>
                    </div>
                    <!--//sub-heard-part-->
                    <div class="graph-visual tables-main">


                        <table style="width: 30%;">
                            <tr>
                                <td><h3 class="inner-tittle two">Cotisations</h3></td>
                                <td>
                                    <form method="post" id="yearSelector">
                                        <select onchange="document.getElementById('yearSelector').submit()"
                                                name="year" class="form-control form-control-sm"
                                                style="padding: unset;">
                                            <?php
                                            $firstYear = 2015;
                                            for ($i = $firstYear; $i <= date("Y") + 1; $i++) {
                                                if ($i == $year) {
                                                    echo '<option value="' . $i . '" selected="selected">' . $i . '</option>' . PHP_EOL;
                                                } else {
                                                    echo '<option value="' . $i . '">' . $i . '</option>' . PHP_EOL;
                                                }
                                            }
                                            ?>

                                        </select>
                                    </form>
                                </td>
                            </tr>
                        </table>

                        <div class="graph">
                            <div class="table-responsive">
                                <form method="post" id="addMember">
                                    <input type="hidden" name="year" value="<?php echo $year; ?>">
                                    <div class="form-group form-inline">
                                        <label for="id">Ajouter la cotisation <?php echo $year; ?> de </label>
                                        <select id="idMember" name="idMember"
                                                required='true' class="form-control form-control-sm"
                                                style="padding: unset;">
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
                                        <select id="membershipType" name="membershipType"
                                                required='true' class="form-control form-control-sm"
                                                style="padding: unset;">
                                            <option value="">--Sélectionnez un type de cotisation--</option>
                                            <option value="Annuelle">Année complète</option>
                                            <option value="Piscine">Uniquement pour nager en piscine</option>
                                        </select>
                                        <button type="submit" name="membershipSubmit" id="membershipSubmit">
                                            Ajouter
                                        </button>
                                        </select>
                                    </div>
                                </form>
                                <?php
                                $sql = "SELECT myclub_member.*, myclub_membership.Type from myclub_member, myclub_membership where myclub_member.ID = myclub_membership.MemberId and myclub_membership.Year = :year order by LastName, FirstName";
                                $query = $dbh->prepare($sql);
                                $query->bindParam('year', $year);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if ($query->rowCount() > 0) { ?>
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Date de naissance</th>
                                            <th>Téléphone</th>
                                            <th>Email</th>
                                            <th>Type</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        foreach ($results as $row) { ?>
                                            <tr class="active">
                                                <th scope="row">
                                                    <a class="tooltips"
                                                       href="show-member-details.php?id=<?php echo $row->ID; ?>">
                                                        <span>Détail</span><i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </th>
                                                <td><?php echo htmlentities($row->LastName); ?></td>
                                                <td><?php echo htmlentities($row->FirstName); ?></td>
                                                <td><?php echo date("d/m/Y", strtotime($row->BirthDate)); ?></td>
                                                <td><?php echo htmlentities($row->MobileNumber); ?></td>
                                                <td><?php echo htmlentities($row->Email); ?></td>
                                                <td><?php echo htmlentities($row->Type); ?></td>
                                                <td>
                                                    <a class="tooltips"
                                                       href="edit-membership-details.php?id=<?php echo $row->ID; ?>"><span>Modifier</span><i
                                                                class="lnr lnr-pencil"></i></a>
                                                </td>
                                            </tr>
                                            <?php $cnt = $cnt + 1;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                <?php } else {
                                    ?>
                                    <h4>Pas de membres en ordre de cotisation pour cette année</h4>
                                    <?php
                                } ?>
                            </div>

                        </div>

                    </div>
                    <!--//graph-visual-->
                </div>
                <!--//outer-wp-->
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
        <!--//content-inner-->
        <!--/sidebar-menu-->
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