<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'])) {
    header('location:dashboard.php');
} else {
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
                            <li class="active">Détail membre</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->

                    <div class="graph-visual tables-main">
                        <h2 class="inner-tittle">Détail de la fiche d'un membre</h2>
                        <div class="graph">
                            <div class="tables">
                                <table class="table">
                                    <?php
                                    $id = $_GET['id'];
                                    $sql = "SELECT * from myclub_member where ID=:id";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':id', $id, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $row) { ?>
                                            <tr class="active">
                                                <td>Nom</td>
                                                <td><?php echo $row->LastName; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Prénom</td>
                                                <td><?php echo $row->FirstName; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Date de naissance</td>
                                                <td><?php echo date("d/m/Y", strtotime($row->BirthDate)); ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Téléphone</td>
                                                <td><?php echo $row->MobileNumber; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Email</td>
                                                <td><?php echo $row->Email; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Adresse</td>
                                                <td><?php echo $row->Address . ' , ' . $row->PostalCode . ' ' . $row->City . ' (' . $row->Country . ')'; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Login</td>
                                                <td><?php echo $row->Login; ?></td>
                                            </tr>
                                            <tr class="active">
                                                <td>Consent à la gestion et la sauvegarde des données personnelles par
                                                    l'administrateur du site
                                                </td>
                                                <td>
                                                    <?php if ($row->RGPD == 1) {
                                                        echo "Oui";
                                                    } else {
                                                        echo "Non";
                                                    } ?>
                                                </td>
                                            </tr>
                                            <tr class="active">
                                                <td for="Mailing">Accepte de recevoir des informations par email</td>
                                                <td>
                                                    <?php if ($row->Mailing == 1) {
                                                        echo "Oui";
                                                    } else {
                                                        echo "Non";
                                                    } ?>
                                                </td>
                                            </tr>
                                            <tr class="active">
                                                <td>Liste des brevets</td>
                                                <td>
                                                    <?php
                                                    $id = $_GET['id'];
                                                    $sql = "SELECT * from myclub_certificates where MemberId=:id order by Label";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':id', $id, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                            echo $row->Label."<br />";
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr class="active">
                                                <td>Cotisations</td>
                                                <td>
                                                    <?php
                                                    $id = $_GET['id'];
                                                    $sql = "SELECT * from myclub_membership where MemberId=:id order by Year";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':id', $id, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                            echo $row->Year." ".$row->Type."<br />";
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } ?>
                                </table>
                                <div class="new">
                                    <p><a href="manage-members.php">Retour vers la liste des membres</a></p>
                                    <div class="clearfix"></div>
                                </div>
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