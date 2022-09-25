<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MAILING'])) {
    header('location:dashboard.php');
} else {
    if (isset($_POST['submit'])) {

        $to=array();

        $sql = "SELECT Email from myclub_member where Mailing = 1";
        $query = $dbh->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        foreach ($results as $row) {
            array_push($to, $row->Email);
        }

        $reply = $_POST['respond'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        $headers = "From:no-reply@amphiprion-durbuy.be \r\n";
        $headers .= "Bcc: ". implode(",", $to) . "\r\n";
        $headers .= "Reply-to:" . $reply." \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8 \r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        $ret = mail(null,$subject,$message,$headers);

        if ($ret) {
            echo '<script>alert("Votre mail a bien été envoyé")</script>';
            echo "<script>window.location.href ='dashboard.php'</script>";
        } else {
            echo '<script>alert("L\'envoi du mail a rencontré un problème")</script>';
            echo "<script>window.location.href ='dashboard.php'</script>";
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
<!--        <script src="https://cdn.tiny.cloud/1/97bdqzplhxol425w4ga2bvnyror01r8okudt24uswatxcd34/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>-->
<!--        <script>-->
<!--            tinymce.init({-->
<!--                selector: 'textarea#editor',-->
<!--                skin: 'bootstrap',-->
<!--                plugins: 'lists, link, image, media',-->
<!--                toolbar: 'h1 h2 bold italic strikethrough blockquote bullist numlist backcolor | link image media | removeformat help',-->
<!--                menubar: false,-->
<!--            });-->
<!--        </script>-->
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
                            <li class="active">Emails</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Envoyer un mail aux membres</h2>
                        <div class="graph-form">
                            <div class="form-body">
                                <form action="" method="post">

                                    <div class="form-group">
                                        <label for="respond">Adresse de réponse</label>
                                        <input type="email" name="respond" value="no-reply@amphiprion-durbuy.be" class="form-control" required='true' />
                                    </div>

                                    <div class="form-group">
                                        <label for="subject">Sujet</label>
                                        <input type="text" name="subject" value="" class="form-control" required='true' />
                                    </div>

                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea id="editor"  name="message" value="" class="form-control" required='true' rows="30" ></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-default" name="submit" id="submit">Envoyer</button>
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