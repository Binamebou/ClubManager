<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else {
    ?>
    <!DOCTYPE HTML>
    <html>
    <head>
        <?php include('includes/head.php'); ?>
        <link rel="stylesheet" href="css/bootstrap-4.1.1.min.css">
        <link rel="stylesheet" href="css/croppie.min.css">
        <script src="js/jquery-3.3.1-min.js"></script>
        <script src="js/croppie-2.6.2.js"></script>
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
                            <li class="active">Photo</li>
                        </ol>
                    </div>
                    <!--/sub-heard-part-->
                    <!--/forms-->
                    <div class="forms-main">
                        <h2 class="inner-tittle">Modifier sa photo de profil</h2>
                        <div class="graph-form">
                            <div class="form-body">


                                <div class="container">
                                    <div class="card-body">
                                        <div class="row" style="padding:5%;">
                                            <div class="col-md-4 text-center">
                                                <input type="file" id="image" required
                                                       onchange="document.getElementById('ok_button').disabled=false;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 text-center">
                                                <div id="upload-demo"></div>
                                            </div>
                                            <div class="col-md-4" style="padding:5%;" id="photo_selection">
                                                <br>
                                                <button class="btn btn-success btn-block btn-upload-image"
                                                        style="margin-top:2%" disabled="disabled" id="ok_button">
                                                    Définir comme photo de profil
                                                </button>
                                                <input type="button" class="btn btn-warning" value="Annuler"
                                                       onClick="document.location.href='member-profile.php'"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body" id="wait_panel" hidden="true">
                                        <div class="row">
                                            <div class="col-md-4 text-center">
                                                Veuillez patienter
                                            </div>
                                        </div>
                                    </div>
                                </div>


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

    <script type="text/javascript">


        var resize = $('#upload-demo').croppie({
            enableExif: true,
            enableOrientation: true,
            viewport: { // Default { width: 100, height: 100, type: 'square' }
                width: 200,
                height: 200,
                type: 'square' //square
            },
            boundary: {
                width: 300,
                height: 300
            }
        });


        $('#image').on('change', function () {
            var reader = new FileReader();
            reader.onload = function (e) {
                resize.croppie('bind', {
                    url: e.target.result
                }).then(function () {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
        });


        $('.btn-upload-image').on('click', function (ev) {
            document.getElementById('photo_selection').hidden = true;
            document.getElementById('wait_panel').hidden = false;
            document.getElementById('image').disabled = true;
            resize.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (img) {
                $.ajax({
                    url: "./croppie.php",
                    type: "POST",
                    data: {"image": img},
                    success: function (data) {
                        html = '<img src="' + img + '" />';
                        $("#preview-crop-image").html(html);
                    }
                }).done(function(){
                    document.location = 'dashboard.php';
                });
            });
        });


    </script>

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
