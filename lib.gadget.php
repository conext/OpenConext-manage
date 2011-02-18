<?php




function mkgadgetResult($title,$num,$rowsPerPage, $qry_result, $fieldsArray) {
	
	$thisUrl = "";
	if(strtoupper($_SERVER[HTTPS]) == "ON") $thisUrl.= "https://";
	$thisUrl.= $_SERVER[SERVER_NAME];
	$thisUrl.= $_SERVER[SCRIPT_NAME];
	$thisUrl.= "?format=html";
	
	$gadgetxml = '<?xml version="1.0" encoding="UTF-8" ?>';
	$gadgetxml .= '<Module>';
	$gadgetxml .= '<ModulePrefs title="'.$title.'"></ModulePrefs>';
	$gadgetxml .= '<Content type="url" href="'.$thisUrl.'"/>';
	$gadgetxml .= '</Module>';
	
}

?>