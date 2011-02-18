<?php

function doquery($db, $db_host, $db_username, $db_password, $querystring, $offset=0,$numrows=20,$debug=false){
	
	mysql_connect($db_host,$db_username,$db_password);
	@mysql_select_db($db) or die( "Unable to select database '$db'");

	// add paging 
	$querystring = $querystring ." LIMIT $offset, $numrows";
	
	if($debug) {
		
		echo "<br/>";
		echo "Query ::<br/>" . $querystring;
		echo "<br/><br/>";
	}
	
	$result=mysql_query($querystring);

	if (!$result) {
	    echo "Could not successfully run query from DB: " . mysql_error();
	    exit;
	}

	mysql_close();

	return $result;
}



?>