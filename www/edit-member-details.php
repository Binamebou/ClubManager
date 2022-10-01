<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'])) {
    header('location:dashboard.php');
} else {
    if (isset($_POST['submit'])) {
        $id = $_GET['id'];
        $crypredPassword = md5($_POST['password']);
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $mobileNumber = $_POST['mobileNumber'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $postalCode = $_POST['postalCode'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $birthDate = $_POST['birthDate'];
        if ($_POST['RGPD']) {
            $rgpd = 1;
        } else {
            $rgpd = 0;
        }
        if ($_POST['Mailing']) {
            $mailing = 1;
        } else {
            $mailing = 0;
        }

        $sql = "update myclub_member set LastName=:lastName, FirstName=:firstName, MobileNumber=:mobileNumber, Email=:email, Address=:address, PostalCode=:postalCode, City=:city, Country=:country, BirthDate=:birthDate, RGPD=:rgpd, Mailing=:mailing, LastUpdate=current_timestamp() where ID=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $query->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $query->bindParam(':mobileNumber', $mobileNumber, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':postalCode', $postalCode, PDO::PARAM_STR);
        $query->bindParam(':city', $city, PDO::PARAM_STR);
        $query->bindParam(':country', $country, PDO::PARAM_STR);
        $query->bindParam(':birthDate', $birthDate, PDO::PARAM_STR);
        $query->bindParam(':rgpd', $rgpd);
        $query->bindParam(':mailing', $mailing);
        $query->execute();
        echo '<script>alert("Le membre a été mis à jour")</script>';
        echo "<script type='text/javascript'> document.location ='manage-members.php'; </script>";
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

                <?php include_once('includes/header.php');?>
                <!--//outer-wp-->
                <div class="outter-wp">
                    <!--/sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Accueil</a></li>
                            <li class="active">Edition membre</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Edition de la fiche d'un membre</h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post">
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
                                            <div class="form-group">
                                                <label for="lastName">Nom</label>
                                                <input type="text" name="lastName" value="<?php  echo $row->LastName;?>" class="form-control" required='true'>
                                            </div>
                                            <div class="form-group">
                                                <label for="firstName">Prénom</label>
                                                <input type="text" name="firstName" value="<?php  echo $row->FirstName;?>" class="form-control" required='true'>
                                            </div>
                                            <div class="form-group">
                                                <label for="birthDate">Date de naissance</label>
                                                <input type="date" name="birthDate" value="<?php  echo $row->BirthDate;?>" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="mobileNumber">Téléphone</label>
                                                <input type="text" name="mobileNumber"" value="<?php  echo $row->MobileNumber;?>" class="form-control" >
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" value="<?php  echo $row->Email;?>" class="form-control" required='true'>
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Adresse</label>
                                                <input type="text" name="address" value="<?php  echo $row->Address;?>" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="postalCode">Code postal</label>
                                                <input type="text" name="postalCode" value="<?php  echo $row->PostalCode;?>" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="city">Localité</label>
                                                <input type="text" name="city" value="<?php  echo $row->City;?>" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="country">Pays</label>
                                                <input type="text" name="country" value="<?php  echo $row->Country;?>" class="form-control">
                                            </div>
                                            <div class="form-inline">
                                                <label for="RGPD">Consent à la gestion et la sauvegarde des données personnelles par l'administrateur du site</label>
                                                <input type="checkbox" name="RGPD" value="1" class="form-inline" <?php  if ($row->RGPD == 1) { echo 'checked="checked"';}?>>
                                            </div>
                                            <div class="form-inline">
                                                <label for="Mailing">Accepte de recevoir des informations par email</label>
                                                <input type="checkbox" name="Mailing" value="1" class="form-inline" <?php  if ($row->Mailing == 1) { echo 'checked="checked"';}?>>
                                            </div>
                                            <?php $cnt = $cnt + 1;
                                        }
                                    } ?>
                                    <button type="submit" class="btn btn-default" name="submit" id="submit">Mettre à jour</button>
                                    <input type="button" class="btn btn-warning" value="Annuler" onClick="history.back();return true;" />
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