<?php
header('Content-Type: application/json');

require __DIR__ . "/../../config/bootstrap.php";

if ($_REQUEST) {

    // Get list of catalogue datasets
	if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getDatasets") {
	#refresh_token(); # TODO
        $var = $_SESSION['User']['Token']['access_token'];
        echo getiPCdatasets($var);
        exit;
    } elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == "getUser") {
        echo getUser($_SESSION['User']['id']);
        exit;

    // Get list of files from catalogue datasets
    }elseif((isset($_REQUEST['action']) && $_REQUEST['action'] == "getDatasetsFiles" && $_REQUEST['file_id'] && $_REQUEST['es_index'])) {
        $var1 = $_REQUEST['file_id'];
        $var2 = $_REQUEST['es_index'];
        echo getiPCdatasetsFiles($var1, $var2);
        exit;
    } elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == "getNCFilePath" && $_REQUEST['file_locator']) {
	//file_locator : "nc:ipc:fdf"
        echo cacadevaca($_REQUEST['file_locator']);
        exit;
    }
}
echo '{}';
exit;

