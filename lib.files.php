<?php

function mkCSVFile($filename,$content,$overwrite=false)
{
	mysql_connect($server, $login, $password);
	mysql_select_db($db);
	
	$fp = fopen($filename, "w");
	
	$res = mysql_query("SELECT * FROM $table");
	
	// fetch a row and write the column names out to the file
	$row = mysql_fetch_assoc($res);
	$line = "";
	$comma = "";
	foreach($row as $name => $value) {
	    $line .= $comma . '"' . str_replace('"', '""', $name) . '"';
	    $comma = ",";
	}
	$line .= "\n";
	fputs($fp, $line);
	
	// remove the result pointer back to the start
	mysql_data_seek($res, 0);
	
	// and loop through the actual data
	while($row = mysql_fetch_assoc($res)) {
	   
	    $line = "";
	    $comma = "";
	    foreach($row as $value) {
	        $line .= $comma . '"' . str_replace('"', '""', $value) . '"';
	        $comma = ",";
	    }
	    $line .= "\n";
	    fputs($fp, $line);
	   
	}
	
	fclose($fp);
	
 
}

function mkCSVFile($filename,$content,$overwrite=false)
{
	mysql_connect($server, $login, $password);
	mysql_select_db($db);
	
	$fp = fopen($filename, "w");
	
	$res = mysql_query("SELECT * FROM $table");
	
	// fetch a row and write the column names out to the file
	$row = mysql_fetch_assoc($res);
	$line = "";
	$comma = "";
	foreach($row as $name => $value) {
	    $line .= $comma . '"' . str_replace('"', '""', $name) . '"';
	    $comma = ",";
	}
	$line .= "\n";
	fputs($fp, $line);
	
	// remove the result pointer back to the start
	mysql_data_seek($res, 0);
	
	// and loop through the actual data
	while($row = mysql_fetch_assoc($res)) {
	   
	    $line = "";
	    $comma = "";
	    foreach($row as $value) {
	        $line .= $comma . '"' . str_replace('"', '""', $value) . '"';
	        $comma = ",";
	    }
	    $line .= "\n";
	    fputs($fp, $line);
	   
	}
	
	fclose($fp);
	
 
}

?>