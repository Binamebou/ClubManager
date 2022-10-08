<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
include('../includes/dbconstants.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'])) {
    header('location:dashboard.php');
} else {
    $id = $_GET['id'];
    $sql = "SELECT * from myclub_member where ID=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $member = $query->fetch(PDO::FETCH_OBJ);

    if (isset($_POST['submit'])) {
        function generateRandomString($length = 10) {
            return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
        }
        $id = $_POST['id'];
        $newPassword = generateRandomString();
        $newCryptedPassword = md5($newPassword);

        $sql = "update myclub_member set Password=:password where ID=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':password', $newCryptedPassword, PDO::PARAM_STR);
        $query->execute();

        $reply = $constants['MAIL_RESPOND_TO'];
        $subject = $constants['MAIL_NEW_PASSWORD_SUBJECT'];
        $message = "<p>Bonjour,</p><br />Le mot de passe de votre compte sur le site <a href='".$constants['SITE_URL']."'>".$constants['SITE_URL']."</a> vient d'être remplacé.<br /><br />";
        $message .= "Vous pouvez vous connecter avec votre login existant : ".$member->Login. " et le nouveau mot de passe ".$newPassword."<br /><br />";
        $message .= "Il est vivement conseillé de modifier ce mot de passe lors de votre prochaine connexion.<br /><br />";
        $message .= $constants['MAIL_NEW_PASSWORD_FOOTER'];
        $message .= "<br /><br />Veuillez s'il vous plaît ne pas répondre à ce mail.";
        $headers = "From:".$constants['MAIL_FROM']." \r\n";
        $headers .= "Reply-to:" . $reply." \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8 \r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        $ret = mail($member->Email,$subject,$message,$headers);

        echo '<script>alert("Le mot de passe a été mis à jour et envoyé par mail")</script>';
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
                            <li class="active">Forcer un changement de mot de passe</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Modification du mot de passe de <?php echo $member->FirstName . " " . $member->LastName ;?></h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post">
                                    <input type="hidden" name="id" value="<?php  echo $member->ID;?>">
                                    <button type="submit" class="btn btn-default" name="submit" id="submit">Mettre à jour le mot de passe et l'envoyer par mail</button>
                                    <input type="button" class="btn btn-warning" value="Annuler" onClick="document.location ='manage-members.php';" />
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