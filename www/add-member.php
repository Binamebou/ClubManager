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

        $crypredPassword = md5($_POST['password']);
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $login = $_POST['login'];
        $mobileNumber = $_POST['mobileNumber'];
        $email = $_POST['email'];
        $password = $_POST['password'];
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

        $sql = "insert into myclub_member(LastName,FirstName,Login,MobileNumber,Email,Password,Address,PostalCode,City,Country,BirthDate, RGPD, Mailing)values(:lastName,:firstName,:login,:mobileNumber,:email,:password,:address,:postalCode,:city,:country,:birthDate,:rgpd,:mailing)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $query->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $query->bindParam(':login', $login, PDO::PARAM_STR);
        $query->bindParam(':mobileNumber', $mobileNumber, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $crypredPassword, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':postalCode', $postalCode, PDO::PARAM_STR);
        $query->bindParam(':city', $city, PDO::PARAM_STR);
        $query->bindParam(':country', $country, PDO::PARAM_STR);
        $query->bindParam(':birthDate', $birthDate, PDO::PARAM_STR);
        $query->bindParam(':rgpd', $rgpd);
        $query->bindParam(':mailing', $mailing);
        $query->execute();

        $LastInsertId = $dbh->lastInsertId();
        if ($LastInsertId > 0) {
            $sql = "insert into myclub_rights(member_id, role_id) values (:id,'USER')";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $LastInsertId, PDO::PARAM_STR);
            $query->execute();
            echo '<script>alert("Le nouveau membre a été ajouté.")</script>';
            echo "<script>window.location.href ='add-member.php'</script>";
        } else {
            echo '<script>alert("Une erreur est survenue, veuillez réessayer")</script>';
        }


    }

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

                <?php include_once('includes/header.php'); ?>
                <!--//outer-wp-->
                <div class="outter-wp">
                    <!--/sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Accuiel</a></li>
                            <li class="active">Ajouter un membre</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Ajouter un membre</h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post">

                                    <div class="form-group">
                                        <label for="lastName">Nom</label>
                                        <input type="text" name="lastName" value="" class="form-control" required='true'>
                                    </div>
                                    <div class="form-group">
                                        <label for="firstName">Prénom</label>
                                        <input type="text" name="firstName" value="" class="form-control" required='true'>
                                    </div>
                                    <div class="form-group">
                                        <label for="birthDate">Date de naissance</label>
                                        <input type="date" name="birthDate" value="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobileNumber">Téléphone</label>
                                        <input type="text" name="mobileNumber"" value="" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" value="" class="form-control" required='true'>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Adresse</label>
                                        <input type="text" name="address" value="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="postalCode">Code postal</label>
                                        <input type="text" name="postalCode" value="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="city">Localité</label>
                                        <input type="text" name="city" value="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="country">Pays</label>
                                        <input type="text" name="country" value="Belgique" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="login">Login</label>
                                        <input type="text" name="login" value="" class="form-control" required='true'>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Mot de passe</label>
                                        <input type="text" name="password" value="" class="form-control" required='true'>
                                    </div>
                                    <div class="form-inline">
                                        <label for="RGPD">Consent à la gestion et la sauvegarde des données personnelles par l'administrateur du site</label>
                                        <input type="checkbox" name="RGPD" value="1" class="form-inline" checked="checked">
                                    </div>
                                    <div class="form-inline">
                                        <label for="Mailing">Accepte de recevoir des informations par email</label>
                                        <input type="checkbox" name="Mailing" value="1" class="form-inline" checked="checked">
                                    </div>

                                    <button type="submit" class="btn btn-default" name="submit" id="submit">Ajouter
                                    </button>
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