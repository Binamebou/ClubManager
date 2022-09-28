<?php
session_start();
error_reporting(0);
include('./../includes/dbconnection.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
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
    <title><?php echo $siteName;?></title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">

    <script type="application/x-javascript"> addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        } </script>
    <!-- Bootstrap Core CSS -->
    <link href="./css/bootstrap.min.css" rel='stylesheet' type='text/css'/>
    <!-- Custom CSS -->
    <link href="./css/style.css" rel='stylesheet' type='text/css'/>
    <!-- Graph CSS -->
    <link href="./css/font-awesome.css" rel="stylesheet">
    <!-- jQuery -->
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet'
          type='text/css'>
    <!-- lined-icons -->
    <link rel="stylesheet" href="./css/icon-font.min.css" type='text/css'/>
    <!-- //lined-icons -->
    <script src="./js/jquery-1.10.2.min.js"></script>
    <!--clock init-->
</head>
<body>
<div class="error_page">

    <div class="error-top">
        <h2 class="inner-tittle page"><?php echo $siteName;?></h2>
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