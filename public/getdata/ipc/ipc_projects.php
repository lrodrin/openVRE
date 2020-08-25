<?php

require __DIR__ . "/../../../config/bootstrap.php";

redirectOutside();

$communities = getCommunities();

?>

<?php
require "../../htmlib/header.inc.php";
require "../../htmlib/js.inc.php";
?>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-container-bg-solid page-sidebar-fixed">
<div class="page-wrapper">

    <?php
    require "../../htmlib/top.inc.php";
    require "../../htmlib/menu.inc.php";
    ?>

    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
            <!-- BEGIN PAGE HEADER-->
            <!-- BEGIN PAGE BAR -->
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="../../home/">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>Get Data</span>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>Nextcloud</span>
                    </li>
                </ul>
            </div>
            <!-- END PAGE BAR -->
            <!-- BEGIN PAGE TITLE-->
            <h1 class="page-title"> Nextcloud
                <small>Your available Nextcloud Projects</small>
            </h1>
            <!-- END PAGE TITLE -->
            <!-- END PAGE HEADER -->

            <?php
            // inject PHP ERROR MESSAGE
            $_SESSION['errorData']['Info'][] = "Data catalogue under construction";

            // print PHP ERROR MESSAGES
            if (isset($_SESSION['errorData'])) {
                foreach ($_SESSION['errorData'] as $subTitle => $txts) {
                    if (count($txts) == 0) {
                        unset($_SESSION['errorData'][$subTitle]);
                    }
                }
            }
            if (isset($_SESSION['errorData']) && $_SESSION['errorData']) {
            if (isset($_SESSION['errorData']['Info'])) {
            ?>

            <div class="alert alert-info"><?php
                } else {
                ?>
                <div class="alert alert-warning"><?php
                    }
                    foreach ($_SESSION['errorData'] as $subTitle => $txts) {
                        print "$subTitle<br/>";
                        foreach ($txts as $txt) {
                            print "<div style=\"margin-left:20px;\">$txt</div>";
                        }
                    }
                    unset($_SESSION['errorData']);
                    ?></div><?php
                }
                ?>

                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light portlet-fit bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-share font-red-sunglo hide"></i>
                                    <span class="caption-subject font-dark bold uppercase">Browse Datasets</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-scrollable">
                                    <table class="table table-striped table-hover table-bordered" id="table-repository">
                                        <tr>
                                            <th> Id</th>
                                            <th> Title</th>
                                            <th> Description</th>
                                            <th> Version</th>
                                            <th> Type</th>
                                            <th> Study</th>
                                            <th> Access credentials</th>
                                            <th> Import</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require "../../htmlib/footer.inc.php";
?>
