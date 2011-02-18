<?php

include_once 'config.inc.php';
include_once 'db.janus.php';
include_once 'lib.query.php';

$qry = "select entityid, state, CONCAT(\"<a href='\", metadataurl, \"' target='_blank'>[xml]</a>\") AS metadataurl, created, user from service_registry.janus__entity ent
INNER JOIN
( 
	select eid, max(revisionid) AS maxrev from service_registry.janus__entity
	group by eid
) entgrp ON ent.eid = entgrp.eid and ent.revisionid = entgrp.maxrev
where type = 'saml20-sp'
order by entityid";


$qry_result = doquery($db, $db_host, $db_username, $db_password, $qry, $offset, $rowsPerPage, $debug);

$num=mysql_numrows($qry_result);

// Define fields to display
$fieldsArray = array("entityid" => "Entity","state" => "Status","created" => "Last Change", "user" => "User", "metadataurl" => "Metadata");
$pageTitle = "EngineBlock Available SPs";

switch ($outputFormat) {
    case "html":
    	// Include displaying stuff
    	include_once 'lib.layout.php';
		include_once 'lib.style.php';
		include_once 'menu.header.php';

		// Handling results display
		// Create a table
		$atable = mkHTMLTable($pageTitle,$num,$rowsPerPage, $qry_result, $fieldsArray);

		// add some styling
		$content .= addFieldSet($atable,$pageTitle);

		// Make a HTML page
		$output = mkHTMLPage($pageTitle,$content,$stylesheet);
		
		break;
        
    case "json":
        include_once 'lib.json.php';
        $output = mkJsonResult($pageTitle,$num,$rowsPerPage, $qry_result, $fieldsArray);
        
        break;
    case "xml":
        echo "i equals 2";
        break;
    case "rss":
        echo "i equals 2";
        break;
    case "gadget":
    	// A gadget wraps the html layout in a gadget xml
    	
    	// Include gadget stuff
    	include_once 'lib.gadget.php';
    	
    	$output = mkgadgetResult($pageTitle,$num,$rowsPerPage, $qry_result, $fieldsArray);
    	
        break;   
    default:
       echo "no format provided";     

}

print($output);

mysql_free_result($qry_result);

?>