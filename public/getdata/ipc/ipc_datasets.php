<?php

require __DIR__ . "/../../../config/bootstrap.php";

?>

<?php
require "../../htmlib/header.inc.php";
require "../../htmlib/js.inc.php"; ?>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-container-bg-solid page-sidebar-fixed">
<div class="page-wrapper">

    <?php require "../../htmlib/top.inc.php"; ?>
    <?php require "../../htmlib/menu.inc.php"; ?>

    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
            <!-- BEGIN PAGE HEADER-->
            <!-- BEGIN PAGE BAR -->
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="home/">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>Get Data</span>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                    <span>From Catalogue</span>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>iPC Catalogue</span>
                    </li>
                </ul>
            </div>
            <!-- END PAGE BAR -->
            <!-- BEGIN PAGE TITLE-->
            <h1 class="page-title"> iPC Catalogue
                <small> List of datasets for iPC projects</small>
            </h1>
            <!-- END PAGE TITLE -->
            <!-- END PAGE HEADER -->

            <!-- BEGIN ERRORS DIV -->
            <?php

            // inject info Message
            // $_SESSION['errorData']['Info'][] = "iPC data catalogue under construction";


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
                        <div id="loading-datatable" class="loadingForm" style="display:none;">
                            <div id="loading-spinner">LOADING</div>
                            <div id="loading-text">It could take a few minutes</div>
                        </div>
                        <div class="portlet light portlet-fit bordered" id="general">
                            <div id="datasets" class="portlet-body">
                                <div class="btn-group" style="float:right;">
                                    <div class="actions">
                                        <a id="datasetsReload" class="btn green"> Reload</a>
                                    </div>
                                </div>
                                <input type="hidden" id="base-url" value="<?php echo $GLOBALS['BASEURL']; ?>"/>
                                <table id="ipcTable"
                                       class="table table-striped table-hover table-bordered"></table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
            <!-- END ERRORS DIV -->
        </div>
		<!-- END CONTENT BODY -->

        <!-- VIEW JSON PART -->
        <div class="modal fade bs-modal" id="modalAnalysis" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Execution Summary</h4>
                    </div>
                    <div class="modal-body table-responsive"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <style type="text/css">

        </style>
        <?php
        require "../../htmlib/footer.inc.php";
        ?>
