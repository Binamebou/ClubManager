<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'] || $_SESSION['ROLE_INSTRUCTOR'])) {
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
                        <a class="btn" href="./members-pdf-list.php" target="_blank">Télécharger au format PDF</a>
                        <?php if ($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER']) { ?>
                            <a class="btn btn-default" href="add-member.php">Ajouter un membre</a>
                        <?php } ?>
                        <div class="graph">
                            <div class="tables">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Date de naissance</th>
                                        <th>Téléphone</th>
                                        <th>Email</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = "SELECT * from myclub_member order by LastName, FirstName";
                                    $query = $dbh->prepare($sql);
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
                                                <td><?php echo htmlentities($row->LastName); ?></td>
                                                <td><?php echo htmlentities($row->FirstName); ?></td>
                                                <td><?php echo date("d/m/Y", strtotime($row->BirthDate)); ?></td>
                                                <td><?php echo htmlentities($row->MobileNumber); ?></td>
                                                <td><?php echo htmlentities($row->Email); ?></td>
                                                <td>
                                                    <?php if ($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER']) { ?>
                                                        <a class="tooltips"
                                                           href="edit-member-details.php?id=<?php echo $row->ID; ?>"><span>Modifier</span><i
                                                                    class="lnr lnr-pencil"></i></a>
                                                        <a class="tooltips"
                                                           href="delete-member.php?id=<?php echo $row->ID; ?>"><span>Supprimer</span><i
                                                                    class="lnr lnr-trash"></i></a>
                                                        <a class="tooltips"
                                                           href="reset-member-password.php?id=<?php echo $row->ID; ?>"><span>Password</span><i
                                                                    class="lnr lnr-sync"></i></a>
                                                    <?php } ?>
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