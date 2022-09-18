<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (strlen($_SESSION['userId'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $adminId = $_SESSION['userId'];
        $newLastName = $_POST['lastname'];
        $newFirstName = $_POST['firstname'];
        $newMobile = $_POST['mobilenumber'];
        $newEmail = $_POST['email'];
        $sql = "update myclub_admin set LastName=:newLastName, FirstName=:newFirstName, MobileNumber=:mobilenumber, Email=:email where ID=:aid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':newLastName', $newLastName, PDO::PARAM_STR);
        $query->bindParam(':newFirstName', $newFirstName, PDO::PARAM_STR);
        $query->bindParam(':email', $newEmail, PDO::PARAM_STR);
        $query->bindParam(':mobilenumber', $newMobile, PDO::PARAM_STR);
        $query->bindParam(':aid', $adminId, PDO::PARAM_STR);
        $query->execute();

        echo '<script>alert("Votre profil a été mis à jour")</script>';
        echo "<script>window.location.href ='admin-profile.php'</script>";

    }
    ?>
    <!DOCTYPE HTML>
    <html>
    <head>
        <title>Gestion du club</title>

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

                <?php include_once('../includes/header.php'); ?>
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
                                    $adminId = $_SESSION['userId'];
                                    $sql = "SELECT * from  myclub_admin where ID = :aid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':aid', $adminId, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    if ($query->rowCount() == 1) {
                                    foreach ($results

                                    as $row) { ?>
                                    <div class="form-group"><label for="lastname">Nom</label>
                                        <input type="text" name="lastname"
                                               value="<?php echo $row->LastName; ?>" class="form-control"
                                               required='true'></div>
                                    <div class="form-group"><label for="firstname">Prénom</label>
                                        <input type="text" name="firstname"
                                               value="<?php echo $row->FirstName; ?>" class="form-control"
                                               required='true'></div>
                                    <div class="form-group"><label for="mobilenumber">Téléphone</label><input
                                                type="text" name="mobilenumber"
                                                value="<?php echo $row->MobileNumber; ?>"
                                                class="form-control"></div>
                                    <div class="form-group"><label for="email">Email
                                            address</label> <input type="email" name="email"
                                                                   value="<?php echo $row->Email; ?>"
                                                                   class="form-control" required='true'></div>
                                    <div class="form-group"><label for="login">Login</label>
                                        <input type="text" name="login" value="<?php echo $row->Login; ?>"
                                               class="form-control" readonly=""></div>
                            </div><?php
                            }
                            } ?>
                            <button type="submit" class="btn btn-default" name="submit" id="submit">Mettre à jour
                            </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include_once('../includes/footer.php'); ?>
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