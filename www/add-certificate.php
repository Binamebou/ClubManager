<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
include('../includes/dbconstants.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'] || $_SESSION['ROLE_INSTRUCTOR'])) {
    header('location:dashboard.php');
} else {

    if (isset($_POST['submit'])) {
        $id = $_POST['id'];
        $memberId = $_POST['memberId'];
        $url = $_POST['url'];
        $ini = strpos($url, 'id=');
        $len = strpos($url, '$', $ini) - $ini;
        $id = substr($url, $ini + 3, $len - 1);

        $doc = new DOMDocument();
        $doc->loadHTMLFile("http://www.adip-international.org/d.php?id=" . $id);
        $certificate_type = $doc->getElementsByTagName("table")[0]
            ->getElementsByTagName("tr")[0]
            ->getElementsByTagName("td")[1]
            ->getElementsByTagName("div")[0]
            ->getElementsByTagName("h3")[0]
            ->getElementsByTagName("b")[0]->nodeValue;
        $certificate_recto = "http://www.adip-international.org/" . $doc->getElementsByTagName("img")[3]->getAttribute("src");
        $certificate_verso = "http://www.adip-international.org/" . $doc->getElementsByTagName("img")[4]->getAttribute("src");
        $memberName = $_POST['memberName'];

        $b64recto = base64_encode(file_get_contents($certificate_recto));
        $b64verso = base64_encode(file_get_contents($certificate_verso));


    } else if (isset($_POST['submit2'])) {
        $id = $_POST['id'];
        $memberId = $_POST['memberId'];
        $certificate_type = $_POST['certificate_type'];
        $certificate_recto = $_POST['certificate_recto'];
        $certificate_verso = $_POST['certificate_verso'];
        $memberName = $_POST['memberName'];

        $sql = "insert into myclub_certificates(MemberId, Label, Recto, Verso) values (:member, :label, :recto, :verso)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':member', $memberId, PDO::PARAM_STR);
        $query->bindParam(':label', $certificate_type, PDO::PARAM_STR);
        $query->bindParam(':recto', $certificate_recto, PDO::PARAM_STR);
        $query->bindParam(':verso', $certificate_verso, PDO::PARAM_STR);
        $query->execute();

        $LastInsertId = $dbh->lastInsertId();
        if ($LastInsertId > 0) {
            echo '<script>alert("Le brevet a été ajouté.")</script>';
            echo "<script>window.location.href ='add-certificate.php'</script>";
        }

    } else if (isset($_POST['submit3'])) {
        $memberId = $_POST['idOption'];
        $sql = "SELECT * from myclub_member where ID=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $memberId, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() > 0) {
            foreach ($results as $row) {
                $memberName = $row->FirstName . " " . $row->LastName;
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

                <?php include_once('includes/header.php'); ?>
                <!--//outer-wp-->
                <div class="outter-wp">
                    <!--/sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Accueil</a></li>
                            <li class="active">Ajouter un brevet à un membre</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Ajouter un brevet <?php if ($memberName) {
                                echo "à " . $memberName;
                            } ?></h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post">
                                    <input id="id" type="hidden" name="id" value="<?php echo $id; ?>"/>
                                    <input id="memberId" type="hidden" name="memberId" value="<?php echo $memberId; ?>"/>
                                    <input id="memberName" type="hidden" name="memberName" value="<?php echo $memberName; ?>"/>
                                    <?php
                                    if (!$memberId) { ?>

                                        <div class="form-group">
                                            <label for="id">Membre concerné</label>
                                            <select id="idOption" name="idOption"
                                                    required='true'>
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

                                        <button type="submit" class="btn btn-default" name="submit3" id="submit3">
                                            Sélectionner cet élève
                                        </button>

                                    <?php } else if (!$certificate_type) {
                                        ?>

                                        <div class="form-group">
                                            <label for="url">URL du brevet à ajouter</label>
                                            <input id="url" type="text" name="url" value="" class="form-control"
                                                   required='true'>
                                        </div>

                                        <button type="submit" class="btn btn-default" name="submit" id="submit">Chercher
                                            le brevet
                                        </button>

                                        <?php
                                    } else {
                                        ?>

                                        <input id="certificate_recto" type="hidden" name="certificate_recto"
                                               value="<?php echo $certificate_recto; ?>"/>
                                        <input id="certificate_verso" type="hidden" name="certificate_verso"
                                               value="<?php echo $certificate_verso; ?>"/>

                                        <div class="form-group">
                                            <label for="certificate_type">Type de brevet</label>
                                            <input id="certificate_type" type="text" name="certificate_type"
                                                   value="<?php echo $certificate_type; ?>" class="form-control"
                                                   readonly="readonly">
                                        </div>
                                        <div class="form-group">
<!--                                            <img src="--><?php //echo $certificate_recto; ?><!--" width="300"/>-->
                                            <img src="data:image/png;base64, <?php echo $b64recto; ?>" width="300"/>
                                        </div>
                                        <div class="form-group">
<!--                                            <img src="--><?php //echo $certificate_verso; ?><!--" width="300"/>-->
                                            <img src="data:image/png;base64, <?php echo $b64verso; ?>" width="300"/>
                                        </div>
                                        <button type="submit" class="btn btn-default" name="submit2" id="submit2">
                                            Ajouter ce brevet
                                        </button>
                                        <?php
                                    }
                                    ?>
                                    <input type="button" class="btn btn-warning" value="Annuler"
                                           onClick="document.location.href='add-certificate.php'"/>
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