<?php
include('./../includes/dbconstants.php');
?>

<div class="sidebar-menu">
    <header class="logo">
        <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a> <a href="dashboard.php"> <span
                    id="logo"> <h3><?php echo $constants['SIDEBAR_HOME_NAME'] ?></h3></span>
            <!--<img id="logo" src="" alt="Logo"/>-->
        </a>
    </header>
    <div style="border-top:1px solid rgba(69, 74, 84, 0.7)"></div>
    <!--/down-->
    <div class="down">
        <?php
        if ($_SESSION['userId']) {
            ?>
            <?php if ($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER']) { ?>
                <a href="dashboard.php"><img src="images/logo_admin.jpg" height="70" width="70"></a>
            <?php } else { ?>
                <a href="dashboard.php"><img src="images/logo.png" height="70" width="100"></a>
            <?php } ?>
            <a href="dashboard.php"><span
                        class=" name-caret"><?php echo $_SESSION['firstName'] . ' ' . $_SESSION['lastName']; ?></span></a>
        <?php } ?>
        <ul>
            <li><a class="tooltips" href="member-profile.php"><span>Profil</span><i class="lnr lnr-user"></i></a>
            </li>
            <li><a class="tooltips" href="change-password.php"><span>Paramètres</span><i class="lnr lnr-cog"></i></a>
            </li>
            <li><a class="tooltips" href="logout.php"><span>Déconnexion</span><i
                            class="lnr lnr-power-switch"></i></a></li>
        </ul>
    </div>
    <!--//down-->
    <div class="menu">
        <ul id="menu">
            <li><a href="dashboard.php"><i class="fa fa-tachometer"></i> <span>Tableau de bord</span></a></li>
            <li><a href="my-certificates.php"><i class="fa fa-credit-card"></i> <span>Mes brevets</span></a></li>
            <li><a href="my-documents.php"><i class="fa fa-paperclip"></i> <span>Mes documents</span></a></li>

            <?php if ($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'] || $_SESSION['ROLE_INSTRUCTOR']) { ?>
                <li id="menu-academico"><a href="#"><i class="fa fa-users"></i> <span>Membres</span> <span
                                class="fa fa-angle-right" style="float: right"></span></a>
                    <ul id="menu-academico-sub">
                        <li id="menu-academico-boletim"><a href="manage-members.php">Liste</a></li>
                            <?php if ($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER']) { ?>
                                <li id="menu-academico-boletim"><a href="manage-membership.php">Cotisations</a></li>
                                <li id="menu-academico-boletim"><a href="add-member.php">Ajouter un membre</a></li>
                                <li id="menu-academico-boletim"><a href="add-member-document.php">Ajouter un document à un membre</a></li>
                            <?php } ?>
                        <li id="menu-academico-boletim"><a href="add-certificate.php">Ajouter un brevet à un membre</a>
                        </li>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MAILING']) { ?>
                <li id="menu-academico"><a href="#"><i class="fa fa-envelope"></i> <span>Emails</span> <span
                                class="fa fa-angle-right" style="float: right"></span></a>
                    <ul id="menu-academico-sub">
                        <li id="menu-academico-avaliacoes"><a href="send-email.php">Envoyer un mail aux membres</a></li>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($_SESSION['ROLE_ADMIN']) { ?>
                <li><a href="manage-rights.php"><i class="fa fa-key"></i> <span>Gestion des droits</span></a></li>
            <?php } ?>

            <!--            <li><a href="manage-client.php"><i class="fa fa-table"></i> <span>Clients List</span></a></li>-->
            <!--            <li><a href="invoices.php"><i class="fa fa-file-text-o"></i> <span>Invoices</span></a></li>-->
            <!---->
            <!--            <li id="menu-academico"><a href="#"><i class="fa fa-table"></i> <span> Reports</span> <span-->
            <!--                            class="fa fa-angle-right" style="float: right"></span></a>-->
            <!--                <ul id="menu-academico-sub">-->
            <!--                    <li id="menu-academico-avaliacoes"><a href="bwdates-reports-ds.php"> B/w dates Reports</a></li>-->
            <!--                    <li id="menu-academico-boletim"><a href="sales-reports.php">Sales Reports</a></li>-->
            <!---->
            <!--                </ul>-->
            <!--            </li>-->
            <!--            <li><a href="search-invoices.php"><i class="fa fa-search"></i> <span>Search Invoice</span></a></li>-->

        </ul>
    </div>
</div>