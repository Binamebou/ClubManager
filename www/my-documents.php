<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else {

    if ($_GET['id'] && $_GET['action'] && $_GET['action'] == "delete") {

        $sql = "SELECT * from myclub_documents where ID=:id AND MemberId=:member";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $_GET['id'], PDO::PARAM_STR);
        $query->bindParam(':member', $_SESSION['userId'], PDO::PARAM_STR);
        $query->execute();
        $document = $query->fetch(PDO::FETCH_OBJ);


        $sql = "DELETE FROM myclub_documents where ID=:id AND MemberId=:member";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $_GET['id'], PDO::PARAM_STR);
        $query->bindParam(':member', $_SESSION['userId'], PDO::PARAM_STR);
        $query->execute();

        if ($document && $document->Path) {
            unlink($document->Path);
        }

        header('location:my-documents.php');
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
                            <li class="active">Mes documents</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->

                    <div class="graph-visual tables-main">
                        <h2 class="inner-tittle">Liste de mes documents</h2>
                        <a href="add-document.php">Ajouter un document</a>
                        <div class="graph">
                            <div class="tables">
                                <table class="table">
                                    <?php
                                    $sql = "SELECT * from myclub_documents where MemberId=:id ORDER BY ValidFrom desc, Type";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':id', $_SESSION['userId'], PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) { ?>
                                        <tr>
                                            <th>Type</th>
                                            <th>Valide ?</th>
                                            <th>du</th>
                                            <th>au</th>
                                            <th>Commentaire</th>
                                            <th></th>
                                        </tr>
                                        <?php
                                        foreach ($results as $row) { ?>
                                            <tr class="active">
                                                <td><?php echo $row->Type; ?></td>
                                                <td><?php if (date('Y-m-d') > $row->ValidFrom && date('Y-m-d') < $row->ValidTo) {
                                                        echo "<span class='glyphicon glyphicon-thumbs-up' style='color:green'> </span>";
                                                    } else {
                                                        echo "<span class='glyphicon glyphicon-thumbs-down' style='color:red'> </span>";
                                                    }; ?></td>
                                                <td><?php echo $row->ValidFrom; ?></td>
                                                <td><?php echo $row->ValidTo; ?></td>
                                                <td><?php echo $row->Comment; ?></td>
                                                <td>
                                                    <a class="tooltips" target="_blank"
                                                       href="download.php?id=<?php echo $row->ID; ?>"><span>Télécharger</span><i
                                                                class="lnr lnr-download"></i></a>
                                                    <a class="tooltips" methods="DELETE"
                                                       href="my-documents.php?action=delete&id=<?php echo $row->ID; ?>"><span>Supprimer</span><i
                                                                class="lnr lnr-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else { ?>
                                        Aucun document n'est présent dans le système.
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