<?php

include_once 'config.inc.php';
include_once 'db.portal.php';
include_once 'lib.query.php';

$qry = "select 	title, 
				author, 
				DATE_FORMAT(added, '%e-%c-%Y, %T') AS added, 
				description, 
				install_count, 
				CONCAT(\"<img src='https://gui.dev.coin.surf.net/\",  screenshot, \"'>\") as icon, 
				CONCAT(\"<a href='\", url, \"' target='_blank'>[xml]</a>\") AS url, 
				CONCAT(\"<img src='icons/\", IF(approved = 'T', 'accept.png', 'cancel.png') , \"'>\") as approved,
				CONCAT(\"<img src='icons/\", IF(supportssso = 'T', 'accept.png', 'cancel.png') , \"'>\") as ssosupport,
				CONCAT(\"<img src='icons/\", IF(supports_groups = 'T', 'accept.png', 'cancel.png') , \"'>\") as groupsupport
		from coin_portal.gadgetdefinition 
		order by title";


$qry_result = doquery($db, $db_host, $db_username, $db_password, $qry, $offset, $rowsPerPage, $debug);

$num=mysql_numrows($qry_result);

// Define fields to display
$fieldsArray = array(	"title" => "Title",
						"author" => "Author",
						"added" => "Date added",
						"description" => "Description",
						"install_count" => "#installs",
						"icon" => "Icon",
						"url" => "View XML",
						"approved" => "Published",
						"ssosupport" => "SSO Support",
						"groupsupport" => "Group Support"
					);



$pageTitle = "Portal Gadget Availability";

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