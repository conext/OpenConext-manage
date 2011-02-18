<?php

include_once 'config.inc.php';
include_once 'db.portal.php';
include_once 'lib.query.php';

$debug=true;

$qry = "select count(`gd`.`id`) AS `num`,
		`gd`.`title` AS `title`,
		`gd`.`author` AS `author`
		from ((`coin_portal`.`tab` `t` join `coin_portal`.`gadget` `g`) 
		join `coin_portal`.`gadgetdefinition` `gd`) 
		where ((`g`.`tab_id` = `t`.`id`) 
		and (`g`.`definition` = `gd`.`id`))";

		IF (isset($thisyear) && isset($thismonth)) {
		$qry .= " AND YEAR(FROM_UNIXTIME(ROUND(g.creation_timestamp/1000))) = ". $thisyear .
				" AND MONTH(FROM_UNIXTIME(ROUND(g.creation_timestamp/1000))) = ". $thismonth;
		}
		
		$qry .= "group by `gd`.`id` 
				order by count(`gd`.`id`) desc";

$qry_result = doquery($db, $db_host, $db_username, $db_password, $qry, $offset, $rowsPerPage, $debug);

$num=mysql_numrows($qry_result);

// Define fields to display
$fieldsArray = array("num" => "Count","title" => "Title","author" => "Author");
$pageTitle = "Portal Gadget Usage";

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