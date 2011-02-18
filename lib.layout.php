<?php

function addFieldSet($content,$legend){
	return ("<fieldset>" . "<legend>" . $legend . "</legend>" .$content . "</fieldset>");
}

function mkHTMLTable($title,$num,$rowsPerPage, $qry_result, $fieldsArray){
	
	$i=0;	
	$fieldsArrayKeys = array_keys($fieldsArray);
	
	$returnStr = "" 	
		."<div id='".$title."'>"
		."<table>"
		."<tr class='column1'>";
	
	for ($j = 0; $j < count($fieldsArray); $j++) {
	   	$returnStr .= "<th scope='col'>". $fieldsArray[$fieldsArrayKeys[$j]]  ."</th>";
	}
	$returnStr .= "</tr>";
	
	while ($row = mysql_fetch_assoc($qry_result)) {
		if($i % 2) { // even row 
			$returnStr .= "<tr class='odd'>";
	    } else { // odd row
	        $returnStr .= "<tr>";
	    }
	    
	    for ($j = 0; $j < count($fieldsArray); $j++) {
	    	$returnStr .= "<td>" . $row[$fieldsArrayKeys[$j]] ."</td>";
	    }
		$returnStr .= "</tr>";
	
	    $i++;
	    
	}
	$returnStr .= "</table>"
		."<span='header'>".$num." results out of max ".$rowsPerPage."</span>"
		."</div>";	
	
	return 	$returnStr;
}

function mkHTMLPage($title,$content,$stylesheet){
	
	$output = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'
		.'<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
	$output .= '<title>SURFConext::Management::'.$title.'</title>';
	$output .= $stylesheet;
	$output .= '</head><body>';
	
	$output .= '<div id="menu" style="float: left; width: 150px; padding: 10px;" >';
	$output .= addmenu();
	$output .= '</div>';
	
	$output .= '<div id="content" style="float: left; width: 850px; padding: 20px; ">';
	$output .= '<div id="queryresults" style="width: 850px;">';
	$output .= $content;
	$output .= '</div>';
	
	$output .= '<div id="exports" style="width: 850px;">';
	$output .= addFieldSet(addExportFormats(),"Export formats");
	$output .= '</div>';
	
	$output .= '<div id="historic" style="width: 850px;">';
	$output .= addFieldSet(addHistoricData(date('Y-m-d')),"Historic Data");
	$output .= '</div>';

	if(debug) {
		$output .= '<div id="debug" style="width: 850px;">';
		$output .= addFieldSet(showDebugMessages($debug,$debugmsg),"Debug");
		$output .= '</div>';
	}
	
	$output .= '</div>';
	$output .= '</body></html>';
	
	return $output;
}

function addExportFormats(){
	$file = $_SERVER["SCRIPT_NAME"];
	$break = Explode('/', $file);
	$pfile = $break[count($break) - 1];
	
	$output .='&nbsp;';
	$output .='<a href="'.$pfile.'?format=csv">csv</a> | ';
	$output .='<a href="'.$pfile.'?format=json">json</a> | ';
	$output .='<a href="'.$pfile.'?format=xml">xml</a> | ';
	$output .='<a href="'.$pfile.'?format=rss">rss</a> | ';
	$output .='<a href="'.$pfile.'?format=gadget">gadget</a> | ';
	
	return $output;
}

function addHistoricData($today,$report_type="test"){

	$output = "";
	
	$filePathArray = pathinfo(__FILE__);
	$filePath = $filePathArray["dirname"] . "/history/";
	$urlPath =  "history/";
	
	$cd = strtotime($today);
	$filenameArray = array();
	
	// Create filenames for historic files
	for ($i = 12; $i > 0; $i--) {
		$thisDate = date('Y-m', mktime(0,0,0,date('m',$cd)-$i,date('d',$cd),date('Y',$cd)));
		$thisFile = $report_type . "-" . $thisDate . ".json";
		
		if (file_exists($filePath.$thisFile)) {
	    	$output .= '<a href="'.$urlPath.$thisDate .'">'.$thisDate.'</a> | ';
		} 

	}
	
	if (strlen($output) == 0) {
		$output = "No historic data found";
	} else {
		$output = '&nbsp;' . $output;
	}

	return $output; 
}

function showDebugMessages($debug=false,$debugmsg){
	if(!debug) {
		return;
	}
	else {
		if(count($debugmsg) > 0) {
			print_r($debugmsg);
		}
	}
}
?>