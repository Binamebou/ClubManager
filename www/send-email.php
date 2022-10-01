<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
include('../includes/dbconstants.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MAILING'])) {
    header('location:dashboard.php');
} else {
    if (isset($_POST['submit'])) {

        $reply = $_POST['respond'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        $headers = "From:".$constants['MAIL_FROM']." \r\n";
        $headers .= "Reply-to:" . $reply." \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8 \r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();


        $sql = "SELECT Email from myclub_member where Mailing = 1";
        $query = $dbh->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        foreach ($results as $row) {
            $ret = mail($row->Email,$subject,$message,$headers);
        }

    }

    ?>
    <!DOCTYPE HTML>
    <html>
    <head>
        <?php include('includes/head.php'); ?>

        <script src="js/nicEdit-latest.js" type="text/javascript"></script>
        <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>

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
                                        <input type="email" name="respond" value="<?php echo $constants['MAIL_RESPOND_TO'] ?>" class="form-control" required='true' />
                                    </div>

                                    <div class="form-group">
                                        <label for="subject">Sujet</label>
                                        <input type="text" name="subject" value="" class="form-control" required='true' />
                                    </div>

                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea id="editor"  name="message" value="" class="form-control" rows="10" ><?php echo $constants['MAIL_DEFAULT_CONTENT'] ?></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-default" name="submit" id="submit">Envoyer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

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