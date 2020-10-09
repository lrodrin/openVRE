<?php

// Get the actual user (the user logged in)
function getUser($userId)
{
    // init variables
    $response = "{}";

    // fetch user
    $user = checkUserIDExists($userId);
    $response = json_encode($user, JSON_PRETTY_PRINT);
    return $response;
}

function getiPCdatasets($var)
{
    $response = "{}";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://catalogue.ipc-project.bsc.es/catalogue_outbox/api/v1/metadata?format=json');
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $var));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    $response = json_encode($response);
    curl_close($curl);
    return $response;
}

function getiPCdatasetsFiles($var1, $var2)
{
    $response = "{}";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://catalogue.ipc-project.bsc.es/es_host/' . $var2 . '/_search?pretty=true&size=10000&q=file_ID:' . $var1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    $response = json_encode($response);
    curl_close($curl);
    return $response;
}

/*function getEuroBioImagingExperimentsFiles($var, $var2, $var3)
{    // TODO change name or delete
    $response = "{}";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://xnat.bmia.nl/data/archive/projects/' . $var . '/subjects/' . $var2 . '/experiments/' . $var3 . '/scans/ALL/files?format=zip');
    $ch = "{}";
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    $response = curl_exec($curl);
    curl_close($curl);
    file_put_contents("'$var'_'$var2'_'$var3'.zip", $response);
    //$response = json_encode($response);

    //return $response;
    return (filesize("'$var'_'$var2'_'$var3'.zip") > 0) ? true : false;
}*/


/* **************************
 *
 * Nextcloud-related Functions
 *
****************************/

function nc_getURL_fromfileId($nc_server="",$nc_fileid=""){
	logger("XXXXXXXXX - entering function nc_getURL_fromfileId");
	$file_url=0;
	if (!isset($GLOBALS['repositories']['nc'][$nc_server])){
		$_SESSION['errorData']['Error'][]="Nextcloud storage '$nc_server' not declared on the VRE. Please, contact with the administrators";
		return $file_url;
	}
	// Query Nextcloud API to get file-path of the given NC file Id 
	$nc_username = 0; 
	$nc_password = 0;
	if (!isset($GLOBALS['repositories']['nc'][$nc_server]['credentials']) || !is_file($GLOBALS['repositories']['nc'][$nc_server]['credentials'])){
		$_SESSION['ErrorData']['Error'][]="VRE repository '$u_namespace:$u_domain' is missing its credentials. Please, contact with the administrators.";
                return $file_url;
	}
	$confFile = $GLOBALS['repositories']['nc'][$nc_server]['credentials'];

	// fetch nextcloud API credentials 
        $credentials = array();
        if (($F = fopen($confFile, "r")) !== FALSE) {
            while (($data = fgetcsv($F, 1000, ";")) !== FALSE) {
                foreach ($data as $a){
                    $r = explode(":",$a);
                    if (isset($r[1])){array_push($credentials,$r[1]);}
                }
            }
            fclose($F);
	}
	if ($credentials[2] != $nc_server){
		$_SESSION['errorData']['Error'][]="Credentials for VRE nextcloud storage '$nc_server' are invalid. Please, contact with the administrators";
		return $file_url;
	}
	$url = "https://$nc_server/remote.php/dav/";

	/*  // Query nextcloud API  - using CURL
	    $response = "{}";
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    $fp = fopen("/home/user/caca.xml", "r");
	    curl_setopt($curl, CURLOPT_FILE, $fp);
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "SEARCH");
	    curl_setopt($curl, CURLOPT_USERPWD, $credentials[0].":".$credentials[1]);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $response = curl_exec($curl);
	    curl_close($curl);
	    fclose($fp);
	 */

	// Query nextcloud API

$body = '<?xml version="1.0" encoding="UTF-8"?>
<d:searchrequest xmlns:d="DAV:" xmlns:oc="http://owncloud.org/ns">
<d:basicsearch>
<d:select>
<d:prop>
<d:displayname/>
</d:prop>
</d:select>
<d:from>
<d:scope>
<d:href>/files/vre/validated_files</d:href>
</d:scope>
</d:from>
<d:where>
<d:eq>
<d:prop>
<oc:fileid/>
</d:prop>
<d:literal>'.$nc_fileid.'</d:literal>
</d:eq>
</d:where>
<d:orderby/>
</d:basicsearch>
</d:searchrequest>';


	    logger("XXXXXXXXX - calling WEBDAV $url with the following SEARCH data:");
	    logger("XXXXXXXXX - $body");
	// Query nextcloud API
	$auth = base64_encode($credentials[0].":".$credentials[1]);
        $context = stream_context_create(array(
	   'http' => array(
	       'header' => array("Authorization: Basic $auth","Content-type: text/xml"), 
               'method' => 'SEARCH',
               'content' => $body,
           )
        ));
        $resp = file_get_contents($url, false, $context);
	    logger("XXXXXXXXX - response is $resp");
        if (!$resp){
		$_SESSION['errorData']['Error'][]="Cannot fetch resource path from Nextcloud '$nc_server'. Storage not responding or returning a mal formated response";
	    	return $file_url;
        }

    /* // sample of the expected response
    $resp = '<?xml version="1.0"?>
<d:multistatus xmlns:d="DAV:" xmlns:s="http://sabredav.org/ns" xmlns:oc="http://owncloud.org/ns" xmlns:nc="http://nextcloud.org/ns">
<d:response>
<d:href>/remote.php/dav/files/vre/validated_files/catalogue_ipc/C835.HCC1143.2.converted.pe_2.fastq</d:href>
<d:propstat>
<d:prop><d:displayname/></d:prop>
<d:status>HTTP/1.1 404 Not Found</d:status>
</d:propstat>
</d:response>
</d:multistatus>';*/

	// Parse file path from nextcloud API response

	$doc = new DOMDocument();
	$doc->loadXML($resp);

	#$nodes = $doc->documentElement;
	
	foreach ($doc->getElementsByTagNameNS('DAV:', '*') as $element) {
	    if ($element->localName == "href"){
		    $file_url = trim($element->nodeValue);
	    }
    	}
	if (!$file_url){
		$_SESSION['errorData']['Error'][]="Cannot fetch resource path from Nextcloud '$nc_server'. Invalid or unexpected storage response.";
		return $file_url;
    	}
	return $file_url;
}
