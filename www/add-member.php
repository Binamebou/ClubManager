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

    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

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
        if ($_POST['SendMail']) {
            $sendMail = 1;
        } else {
            $sendMail = 0;
        }

        $sql = "insert into myclub_member(LastName,FirstName,Login,MobileNumber,Email,Password,Address,PostalCode,City,Country,BirthDate, RGPD, Mailing, active)values(:lastName,:firstName,:login,:mobileNumber,:email,:password,:address,:postalCode,:city,:country,:birthDate,:rgpd,:mailing, 1)";
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

            if ($sendMail && $email) {
                $reply = $constants['MAIL_RESPOND_TO'];
                $subject = $constants['MAIL_NEW_MEMBER_SUBJECT'];
                $message = "<p>Bonjour,</p><br />Un compte sur le site <a href='".$constants['SITE_URL']."'>".$constants['SITE_URL']."</a> vient d'être créé pour vous.<br /><br />";
                $message .= "Il vous permet de gérer vos information personnelles et vos préférences de notifications (recevoir des emails du club ou pas par exemple)<br /><br />";
                $message .= "Vous pouvez vous connecter avec ce login : ".$login. " et le mot de passe ".$_POST['password']."<br /><br />";
                $message .= "Il est vivement conseillé de modifier ce mot de passe lors de votre première connexion.<br /><br />";
                $message .= $constants['MAIL_NEW_MEMBER_FOOTER'];
                $message .= "<br /><br />Veuillez s'il vous plaît ne pas répondre à ce mail.";
                $headers = "From:".$constants['MAIL_FROM']." \r\n";
                $headers .= "Reply-to:" . $reply." \r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8 \r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();
                $ret = mail($email,$subject,$message,$headers);
            }

            echo "<script>window.location.href ='manage-members.php'</script>";
        } else {
            echo '<script>alert("Une erreur est survenue, veuillez réessayer")</script>';
        }


    }

    ?>
    <!DOCTYPE HTML>
    <html lang="fr">
    <head>
        <?php include('includes/head.php'); ?>
        <script>

            function normalize(entry) {
                entry = entry.replace(/[éèëê]/g, "e");
                entry = entry.replace(/[àâä]/g, "a");
                entry = entry.replace(/[îï]/g, "i");
                entry = entry.replace(/[ôö]/g, "o");
                entry = entry.replace(/[ûü]/g, "u");
                entry = entry.replace(/[ç]/g, "c");
                entry = entry.replace(/[^A-Za-z0-9\.]/g, "");
                return entry.toLowerCase();
            }

            function fillLogin() {
                let login = document.getElementById("firstName").value + "." + document.getElementById("lastName").value;
                document.getElementById("login").value = normalize(login)
            }
        </script>
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
                                        <input id="lastName" type="text" name="lastName" value="" class="form-control"
                                               required='required' onchange="fillLogin()">
                                    </div>
                                    <div class="form-group">
                                        <label for="firstName">Prénom</label>
                                        <input id="firstName" type="text" name="firstName" value="" class="form-control"
                                               required='required' onchange="fillLogin()">
                                    </div>
                                    <div class="form-group">
                                        <label for="birthDate">Date de naissance</label>
                                        <input type="date" name="birthDate" id="birthDate" value="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobileNumber">Téléphone</label>
                                        <input type="text" name="mobileNumber" id="mobileNumber" value="" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" name="email" value="" class="form-control" required='required'>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Adresse</label>
                                        <input type="text" name="address" id="address" value="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="postalCode">Code postal</label>
                                        <input type="text" name="postalCode" id="postalCode" value="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="city">Localité</label>
                                        <input type="text" name="city" id="city" value="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="country">Pays</label>
                                        <input type="text" name="country" id="country" value="Belgique" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="login">Login</label>
                                        <input id="login" type="text" name="login" value="" class="form-control"
                                               required='required'>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Mot de passe (un mot de passe aléatoire a été généré)</label>
                                        <input id="password" type="password" name="password" value="<?php echo generateRandomString();?>" class="form-control"
                                               required='required'>
                                    </div>
                                    <div class="form-inline">
                                        <label for="RGPD">Consent à la gestion et la sauvegarde des données personnelles
                                            par l'administrateur du site</label>
                                        <input id="RGPD" type="checkbox" name="RGPD" value="1" class="form-inline"
                                               checked="checked">
                                    </div>
                                    <div class="form-inline">
                                        <label for="Mailing">Accepte de recevoir des informations par email</label>
                                        <input id="Mailing" type="checkbox" name="Mailing" value="1" class="form-inline"
                                               checked="checked">
                                    </div>

                                    <div class="form-inline">
                                        <label for="SendMail">Envoyer les informations de connexion par email au nouveau membre</label>
                                        <input id="SendMail" type="checkbox" name="SendMail" value="1" class="form-inline"
                                               checked="checked">
                                    </div>

                                    <button type="submit" class="btn btn-default" name="submit" id="submit">Ajouter
                                    </button>
                                    <input type="button" class="btn btn-warning" value="Annuler"
                                           onClick="history.back();return true;"/>
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