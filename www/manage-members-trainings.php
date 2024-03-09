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
                            <li class="active">Liste des formations en cours</li>
                        </ol>
                    </div>
                    <!--//sub-heard-part-->
                    <div class="graph-visual tables-main">


                        <h3 class="inner-tittle two">Liste des formations</h3>
                        <a class="btn btn-default" href="add-member-training.php">Ajouter une formation à un membre</a>
                        <div class="graph">
                            <div class="table-responsive">
                                <form method="post">
                                    <input type="checkbox"
                                           name="showArchived" <?php if ($showArchived == 1) echo 'checked="checked"'; ?>
                                           onchange="this.form.submit()"> Voir aussi les formations terminées
                                </form>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Brevet</th>
                                        <th>Date de début</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $activeStatus = $showArchived == 1 ? 0 : 1;
                                    $sql = "SELECT m.LastName as LastName, m.FirstName as FirstName, t.Type as Type, t.Active as Active, t.ID as ID,
                                            (SELECT a.ActionDate from myclub_training_actions a where a.TrainingId = t.ID AND a.Type = 'CREATED') as BeginDate
                                            from myclub_member as m, myclub_trainings as t where t.memberId = m.ID AND m.active = 1 AND t.Active >= :activeStatus order by m.LastName, m.FirstName, t.Type";
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
                                                       href="show-member-training-details.php?id=<?php echo $row->ID; ?>">
                                                        <span>Détail</span><i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </th>
                                                <td <?php if ($row->Active == 0) echo 'class="archived"'; ?>><?php echo htmlentities($row->LastName); ?></td>
                                                <td <?php if ($row->Active == 0) echo 'class="archived"'; ?>><?php echo htmlentities($row->FirstName); ?></td>
                                                <td <?php if ($row->Active == 0) echo 'class="archived"'; ?>><?php echo htmlentities($utils->getTrainingLabel($row->Type)); ?></td>
                                                <td <?php if ($row->Active == 0) echo 'class="archived"'; ?>><?php echo date("d/m/Y", strtotime($row->BeginDate)); ?></td>
                                                <td>
                                                    <a class="tooltips"
                                                       href="edit-member-training.php?id=<?php echo $row->ID; ?>"><span>Modifier</span><i
                                                                class="lnr lnr-pencil"></i></a>
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