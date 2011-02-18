<?php

include_once 'config.inc.php';
include_once 'db.eb.php';
include_once 'lib.query.php';

// Query Handling

// This old query does not need to go between databased, but cannot show displaynames 
//$qry = "SELECT count(loginstamp) as num, spentityid FROM log_logins GROUP BY spentityid ORDER BY NUM DESC";

$qry = "SELECT count(eb.loginstamp) as count, sr.displayName AS EntityName
		FROM engine_block.log_logins eb,
		(
			SELECT jm.value AS 'displayName', je.entityid FROM service_registry.janus__metadata jm, service_registry.janus__entity je
			WHERE jm.eid = je.eid
			AND jm.revisionid = je.revisionid
			AND jm.`key` = 'displayName:nl' 
			ORDER BY je.revisionid DESC
		) AS sr 
		WHERE eb.spentityid = sr.entityid
		GROUP BY spentityid 
		ORDER BY count DESC, EntityName asc";

$qry_result = doquery($db, $db_host, $db_username, $db_password, $qry, $offset, $rowsPerPage, $debug);

// Count results
$num=mysql_numrows($qry_result);

// Define fields to display
$fieldsArray = array("count"=>"Num Logins","EntityName"=>"Entity Name");
$pageTitle = "EngineBlock_SP_Logins";

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

}

print($output);

mysql_free_result($qry_result);

?>