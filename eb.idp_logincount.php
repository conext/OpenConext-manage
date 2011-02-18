<?php

include_once 'config.inc.php';
include_once 'db.eb.php';
include_once 'lib.query.php';

// Query Handling
$qry = "SELECT count(loginstamp) as num, idpentityid FROM log_logins GROUP BY idpentityid ORDER BY NUM DESC";

$qry_result = doquery($db, $db_host, $db_username, $db_password, $qry, $offset, $rowsPerPage, $debug);

// Count results
$num=mysql_numrows($qry_result);

// Define fields to display
$fieldsArray = array("num"=>"Num Logins","idpentityid"=>"Entity");
$pageTitle = "EngineBlock_IDP_Logins";

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
        echo "Not supported yet";
        break;
    case "rss":
        echo "Not supported yet";
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
s