<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!$_SESSION['ROLE_ADMIN']) {
    header('location:dashboard.php');
} else {

    if (isset($_POST['submit'])) {

        $role = "ADMIN";
        $sql = "delete from myclub_rights where role_id = :role";
        $query = $dbh->prepare($sql);
        $query->bindParam(':role', $role, PDO::PARAM_STR);
        $query->execute();
        foreach ($_POST['isAdmin'] as $id) {

            $sql = "insert into myclub_rights(member_id, role_id) values (:id, :role)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id);
            $query->bindParam(':role', $role, PDO::PARAM_STR);
            $query->execute();
        }

        $role = "MAILING";
        $sql = "delete from myclub_rights where role_id = :role";
        $query = $dbh->prepare($sql);
        $query->bindParam(':role', $role, PDO::PARAM_STR);
        $query->execute();
        foreach ($_POST['isMailing'] as $id) {
            $sql = "insert into myclub_rights(member_id, role_id) values (:id, :role)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id);
            $query->bindParam(':role', $role, PDO::PARAM_STR);
            $query->execute();
        }

        $role = "MANAGER";
        $sql = "delete from myclub_rights where role_id = :role";
        $query = $dbh->prepare($sql);
        $query->bindParam(':role', $role, PDO::PARAM_STR);
        $query->execute();
        foreach ($_POST['isManager'] as $id) {
            $sql = "insert into myclub_rights(member_id, role_id) values (:id, :role)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id);
            $query->bindParam(':role', $role, PDO::PARAM_STR);
            $query->execute();
        }

        $role = "INSTRUCTOR";
        $sql = "delete from myclub_rights where role_id = :role";
        $query = $dbh->prepare($sql);
        $query->bindParam(':role', $role, PDO::PARAM_STR);
        $query->execute();
        foreach ($_POST['isInstructor'] as $id) {
            $sql = "insert into myclub_rights(member_id, role_id) values (:id, :role)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id);
            $query->bindParam(':role', $role, PDO::PARAM_STR);
            $query->execute();
        }

        $role = "USER";
        $sql = "delete from myclub_rights where role_id = :role";
        $query = $dbh->prepare($sql);
        $query->bindParam(':role', $role, PDO::PARAM_STR);
        $query->execute();
        foreach ($_POST['isUser'] as $id) {
            $sql = "insert into myclub_rights(member_id, role_id) values (:id, :role)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id);
            $query->bindParam(':role', $role, PDO::PARAM_STR);
            $query->execute();
        }

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
                <!-- header-starts -->
                <?php include_once('includes/header.php'); ?>
                <!-- //header-ends -->
                <!--outter-wp-->
                <div class="outter-wp">
                    <!--sub-heard-part-->
                    <div class="sub-heard-part">
                        <ol class="breadcrumb m-b-0">
                            <li><a href="dashboard.php">Accueil</a></li>
                            <li class="active">Gestion des droits</li>
                        </ol>
                    </div>
                    <!--//sub-heard-part-->
                    <div class="forms-main">

                        <h3 class="inner-tittle two">Droits des utilisateurs</h3>

                        <div class="graph-form">
                            <div class="form-body">
                                <form method="post">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <th>Login</th>
                                                <th>Admin</th>
                                                <th>Mails</th>
                                                <th>Manager</th>
                                                <th>Instructeur</th>
                                                <th>User</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                            $sql = "SELECT m.ID as ID, m.LastName as LastName, m.FirstName as FirstName, m.Login as Login
     , (select count(1) from myclub_rights r where r.member_id = m.ID and r.role_id = 'ADMIN') as isAdmin
     , (select count(1) from myclub_rights r where r.member_id = m.ID and r.role_id = 'MAILING') as isMailing
     , (select count(1) from myclub_rights r where r.member_id = m.ID and r.role_id = 'MANAGER') as isManager
     , (select count(1) from myclub_rights r where r.member_id = m.ID and r.role_id = 'USER') as isUser
     , (select count(1) from myclub_rights r where r.member_id = m.ID and r.role_id = 'INSTRUCTOR') as isInstructor
    from myclub_member m   where m.active = 1 order by m.LastName, m.FirstName";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                                            $cnt = 1;
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $row) { ?>
                                                    <tr class="active">
                                                        <td><?php echo htmlentities($row->LastName); ?></td>
                                                        <td><?php echo htmlentities($row->FirstName); ?></td>
                                                        <td><?php echo htmlentities($row->Login); ?></td>
                                                        <td>
                                                            <input type="checkbox"
                                                                   name="isAdmin[]"
                                                                   value="<?php echo $row->ID; ?>"
                                                            <?php if ($row->isAdmin) {
                                                                echo "checked = 'checked'";
                                                            } ?>" />
                                                        </td>
                                                        <td><input type="checkbox"
                                                                   name="isMailing[]"
                                                                   value="<?php echo $row->ID; ?>"
                                                            <?php if ($row->isMailing) {
                                                                echo "checked = 'checked'";
                                                            } ?>" />
                                                        </td>
                                                        <td><input type="checkbox"
                                                                   name="isManager[]"
                                                                   value="<?php echo $row->ID; ?>"
                                                            <?php if ($row->isManager) {
                                                                echo "checked = 'checked'";
                                                            } ?>" />
                                                        </td>
                                                        <td><input type="checkbox"
                                                                   name="isInstructor[]"
                                                                   value="<?php echo $row->ID; ?>"
                                                            <?php if ($row->isInstructor) {
                                                                echo "checked = 'checked'";
                                                            } ?>" />
                                                        </td>
                                                        <td><input type="checkbox" name="isUser[]"
                                                                   value="<?php echo $row->ID; ?>"
                                                            <?php if ($row->isUser) {
                                                                echo "checked = 'checked'";
                                                            } ?>" />
                                                        </td>
                                                    </tr>
                                                    <?php $cnt = $cnt + 1;
                                                } ?>
                                            <?php } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="submit" class="btn btn-default" name="submit" id="submit">
                                        Sauver
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--//outer-wp-->
                    <?php include_once('includes/footer.php'); ?>
                </div>
            </div>
            <!--//content-inner-->
            <!--/sidebar-menu-->
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