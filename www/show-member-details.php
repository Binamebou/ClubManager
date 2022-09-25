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
        <title><?php echo $siteName;?></title>

        <script type="application/x-javascript"> addEventListener("load", function () {
                setTimeout(hideURLbar, 0);
            }, false);

            function hideURLbar() {
                window.scrollTo(0, 1);
            } </script>
        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css'/>
        <!-- Custom CSS -->
        <link href="css/style.css" rel='stylesheet' type='text/css'/>
        <!-- Graph CSS -->
        <link href="css/font-awesome.css" rel="stylesheet">
        <!-- jQuery -->
        <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet'
              type='text/css'>
        <!-- lined-icons -->
        <link rel="stylesheet" href="css/icon-font.min.css" type='text/css'/>
        <!-- //lined-icons -->
        <script src="js/jquery-1.10.2.min.js"></script>
        <!--clock init-->
        <script src="js/css3clock.js"></script>
        <!--Easy Pie Chart-->
        <!--skycons-icons-->
        <script src="js/skycons.js"></script>
        <!--//skycons-icons-->
    </head>
    <body>
    <div class="page-container">
        <!--/content-inner-->
        <div class="left-content">
            <div class="inner-content">

                <?php include_once('includes/header.php');?>
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
                                                <td><?php echo date("d/m/Y",strtotime($row->BirthDate)); ?></td>
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