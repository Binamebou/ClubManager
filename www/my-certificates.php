<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
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
                            <li class="active">Mes brevets</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->

                    <div class="graph-visual tables-main">
                        <h2 class="inner-tittle">Liste de mes brevets</h2>
                        <div class="graph">
                            <div class="tables">
                                <table class="table">
                                    <?php
                                    $sql = "SELECT * from myclub_certificates where MemberId=:id";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':id', $_SESSION['userId'], PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $row) { ?>
                                            <tr class="active">
                                                <td><?php echo $row->Label; ?></td>
                                                <td>
                                                    <img src="data:image/png;base64, <?php echo base64_encode(file_get_contents($row->Recto)); ?>"
                                                         width="300"/>
                                                </td>
                                                <td>
                                                    <img src="data:image/png;base64, <?php echo base64_encode(file_get_contents($row->Verso)); ?>"
                                                         width="300"/>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else { ?>
                                        Aucun brevet n'est encodé dans le système, contactez votre moniteur pour ajouter vos brevets manquants.
                                        <?php
                                    }
                                    ?>
                                </table>
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