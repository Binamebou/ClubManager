<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (strlen($_SESSION['userId'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $id = $_GET['id'];
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $mobileNumber = $_POST['mobileNumber'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $postalCode = $_POST['postalCode'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $birthDate = $_POST['birthDate'];

        $sql = "update myclub_member set LastName=:lastName, FirstName=:firstName, MobileNumber=:mobileNumber, Email=:email, Address=:address, PostalCode=:postalCode, City=:city, Country=:country, BirthDate=:birthDate where ID=:id";
        $query = $dbh->prepare($sql);
//$query->bindParam(':acctid',$acctid,PDO::PARAM_STR);
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
        $query->execute();

        echo '<script>alert("Votre profil a été mis à jour")</script>';
        echo "<script>window.location.href ='member-profile.php'</script>";

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
                            <li><a href="dashboard.php">Accueil</a></li>
                            <li class="active">Mon Profil</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Mon profil</h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post">
                                    <?php
                                    $id = $_SESSION['userId'];
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
                                            <?php $cnt = $cnt + 1;
                                        }
                                    } ?>
                            <button type="submit" class="btn btn-default" name="submit" id="submit">Mettre à jour
                            </button>
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