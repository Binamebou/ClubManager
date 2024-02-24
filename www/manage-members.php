<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
require_once('utils.php');

if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'] || $_SESSION['ROLE_INSTRUCTOR'])) {
    header('location:dashboard.php');
} else {
    $showArchived = $_POST['showArchived'] ? 1 : 0;
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
                <!-- header-starts -->
                <?php include_once('includes/header.php'); ?>
                <!-- //header-ends -->
                <!--outter-wp-->
                <div class="outter-wp">
                    <!--sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Accueil</a></li>
                            <li class="active">Liste des membres</li>
                        </ol>
                    </div>
                    <!--//sub-heard-part-->
                    <div class="graph-visual tables-main">


                        <h3 class="inner-tittle two">Liste des membres</h3>
                        <?php if ($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER']) { ?>
                            <a class="btn btn-default" href="add-member.php">Ajouter un membre</a>
                        <?php } ?>
                        <a href="./members-pdf-list.php" target="_blank"><img src="pdf/pdf1.png" alt="Télécharger au format PDF" style="width: 30px; height: 40px;"></a>
                        <a href="./members-xls-list.php" target="_blank"><img src="xls/xls1.png" alt="Télécharger au format Excel" style="width: 60px; height: 50px;"></a>
                        <div class="graph">
                            <div class="table-responsive">
                                <form method="post">
                                    <input type="checkbox" name="showArchived" <?php if ($showArchived == 1) echo 'checked="checked"'; ?> onchange="this.form.submit()">  Voir aussi les membres archivés
                                </form>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Brevet</th>
                                        <th>Date de naissance</th>
                                        <th>Téléphone</th>
                                        <th>Email</th>
                                        <th>DAN</th>
                                        <th>Med</th>
                                        <th><?php echo date("Y"); ?> </th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $activeStatus = $showArchived == 1 ? 0 : 1;
                                    $sql = "SELECT *
                                            , IFNULL((select 'OK' from myclub_documents where Type = 'Assurance DAN' and MemberId = myclub_member.ID and ValidFrom < CURDATE() and ValidTo BETWEEN CURDATE() + INTERVAL 1 MONTH AND CURDATE() + INTERVAL 1 YEAR
                                               union
                                               select 'WARN' from myclub_documents where Type = 'Assurance DAN' and MemberId = myclub_member.ID and ValidFrom < CURDATE() and ValidTo > CURDATE() and ValidTo < CURDATE() + INTERVAL 1 MONTH), 'KO') as DAN,
                                           IFNULL((select 'OK' from myclub_documents where Type = 'Certificat Médical' and MemberId = myclub_member.ID and ValidFrom < CURDATE() and ValidTo BETWEEN CURDATE() + INTERVAL 1 MONTH AND CURDATE() + INTERVAL 1 YEAR
                                                   union
                                                   select 'WARN' from myclub_documents where Type = 'Certificat Médical' and MemberId = myclub_member.ID and ValidFrom < CURDATE() and ValidTo > CURDATE() and ValidTo < CURDATE() + INTERVAL 1 MONTH), 'KO') as MED,
                                           IFNULL((select 'OK' from myclub_membership where  MemberId = myclub_member.ID and Year = year(curdate())), 'KO') as COT from myclub_member where active >= :activeStatus order by LastName, FirstName";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':activeStatus', $activeStatus);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $row) { ?>
                                            <tr class="active">
                                                <th scope="row">
                                                    <a class="tooltips"
                                                       href="show-member-details.php?id=<?php echo $row->ID; ?>">
                                                        <span>Détail</span><i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </th>
                                                <td <?php if ($row->active == 0) echo 'class="archived"'; ?>><?php echo htmlentities($row->LastName); ?></td>
                                                <td <?php if ($row->active == 0) echo 'class="archived"'; ?>><?php echo htmlentities($row->FirstName); ?></td>
                                                <td <?php if ($row->active == 0) echo 'class="archived"'; ?>><?php echo htmlentities($utils->getCertificateLabel($row->HighestCertificate)); ?></td>
                                                <td <?php if ($row->active == 0) echo 'class="archived"'; ?>><?php echo date("d/m/Y", strtotime($row->BirthDate)); ?></td>
                                                <td <?php if ($row->active == 0) echo 'class="archived"'; ?>><?php echo htmlentities($row->MobileNumber); ?></td>
                                                <td <?php if ($row->active == 0) echo 'class="archived"'; ?>><?php echo htmlentities($row->Email); ?></td>
                                                <td><?php if ($row->DAN == "OK") {
                                                        echo "<span class='glyphicon glyphicon-thumbs-up' style='color:green' title='DAN en ordre'> </span>";
                                                    } else if ($row->DAN == "WARN") {
                                                        echo "<span class='glyphicon glyphicon-thumbs-up' style='color:orange' title='DAN bientôt expirée'> </span>";
                                                    } else if ($row->DAN == "KO") {
                                                        echo "<span class='glyphicon glyphicon-thumbs-down' style='color:red' title='DAN expirée'> </span>";
                                                    } else {
                                                        echo "<span class='glyphicon glyphicon-question-sign' style='color:grey' title='Pas de DAN'></span>";
                                                    } ?></td>
                                                <td><?php if ($row->MED == "OK") {
                                                        echo "<span class='glyphicon glyphicon-thumbs-up' style='color:green' title='Certificat en ordre'> </span>";
                                                    } else if ($row->MED == "WARN") {
                                                        echo "<span class='glyphicon glyphicon-thumbs-up' style='color:orange' title='Certificat bientôt expiré'> </span>";
                                                    } else if ($row->MED == "KO") {
                                                        echo "<span class='glyphicon glyphicon-thumbs-down' style='color:red' title='Certificat expiré'> </span>";
                                                    } else {
                                                        echo "<span class='glyphicon glyphicon-question-sign' style='color:grey' title='Pas de certificat'> </span>";
                                                    } ?></td>
                                                <td><?php if ($row->COT == "OK") {
                                                        echo "<span class='glyphicon glyphicon-thumbs-up' style='color:green' title='Cotisation en ordre'> </span>";
                                                    } else if ($row->COT == "WARN") {
                                                        echo "<span class='glyphicon glyphicon-thumbs-up' style='color:orange' title='Cotisation à payer bientôt'> </span>";
                                                    } else if ($row->COT == "KO") {
                                                        echo "<span class='glyphicon glyphicon-thumbs-down' style='color:red' title='Cotisation non payée'> </span>";
                                                    } else {
                                                        echo "<span class='glyphicon glyphicon-question-sign' style='color:grey' title='Pas de cotisation'> </span>";
                                                    } ?></td>
                                                <td>
                                                    <?php if ($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER']) { ?>
                                                        <?php if ($row->active == 1) { ?>
                                                            <a class="tooltips"
                                                               href="edit-member-details.php?id=<?php echo $row->ID; ?>"><span>Modifier</span><i
                                                                    class="lnr lnr-pencil"></i></a>
                                                            <a class="tooltips"
                                                               href="archive-member.php?id=<?php echo $row->ID; ?>"><span>Archiver</span><i
                                                                    class="lnr lnr-trash"></i></a>
                                                            <a class="tooltips"
                                                               href="reset-member-password.php?id=<?php echo $row->ID; ?>"><span>Password</span><i
                                                                    class="lnr lnr-sync"></i></a>
                                                        <?php } else { ?>
                                                            <a class="tooltips"
                                                               href="delete-member.php?id=<?php echo $row->ID; ?>"><span>Supprimer</span><i
                                                                    class="lnr lnr-trash" style='color:red'></i></a>
                                                            <a class="tooltips"
                                                               href="restore-member.php?id=<?php echo $row->ID; ?>"><span>restaurer</span><i
                                                                    class="lnr lnr-undo" style='color:green'></i></a>
                                                        <?php }
                                                    } ?>
                                                </td>
                                            </tr>
                                            <?php $cnt = $cnt + 1;
                                        }
                                    } ?>
                                    </tbody>
                                </table>
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