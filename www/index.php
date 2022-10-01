<?php
session_start();
error_reporting(0);
include('./../includes/dbconnection.php');
include('./../includes/dbconstants.php');
require_once('utils.php');
if (isset($_POST['login'])) {

    $utils = new utils();
//    $username = utils::normalize($_POST['username']);
    $username = $utils->normalize($_POST['username']);
    $password = md5($_POST['password']);
    $sql = "SELECT * FROM myclub_member WHERE upper(Login)=upper(:username) and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $_SESSION['userId'] = $result->ID;
            $_SESSION['lastName'] = $result->LastName;
            $_SESSION['firstName'] = $result->FirstName;
        }
        $_SESSION['login'] = $_POST['username'];

        $sql = "SELECT * FROM myclub_rights WHERE member_id = :userid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':userid', $_SESSION['userId'], PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                $_SESSION['ROLE_' . $result->role_id] = true;
            }
        }
        echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
    } else {
        echo "<script>alert('Login ou mot de passe inconnu');</script>";
    }
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <?php include('includes/head.php'); ?>
</head>
<body>
<div class="error_page">

    <div class="error-top">
        <h2 class="inner-tittle page"><?php echo $constants['SITE_NAME'];?></h2>
        <div class="login">

            <div class="buttons login">
                <h3 class="inner-tittle t-inner" style="color: lightblue">Connexion</h3>
            </div>
            <form id="login" method="post" name="login">
                <input type="text" class="text" value="Votre login" onfocus="this.value = '';"
                       onblur="if (this.value == '') {this.value = 'Votre login';}" name="username" required="true">
                <input type="password" value="Votre mot de passe" onfocus="this.value = '';"
                       onblur="if (this.value == '') {this.value = 'Votre mot de passe';}" name="password"
                       required="true">
                <div class="submit"><input type="submit" onclick="myFunction()" value="Connexion" name="login"></div>
                <div class="clearfix"></div>

                <div class="new">
                    <p><a href="./forgot-password.php">Mot de passe oublié ?</a></p>
                    <div class="clearfix"></div>
                </div>

            </form>
        </div>


    </div>


    <!--//login-top-->
</div>

<!--//login-->
<!--footer section start-->
<div class="footer">

    <?php include_once('./includes/footer.php'); ?>
</div>
<!--footer section end-->
<!--/404-->
<!--js -->
<script src="./js/jquery.nicescroll.js"></script>
<script src="./js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="./js/bootstrap.min.js"></script>
</body>
</html>